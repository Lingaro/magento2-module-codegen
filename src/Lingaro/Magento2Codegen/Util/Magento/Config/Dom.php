<?php

/**
 * Copyright © 2023 Lingaro sp. z o.o. All rights reserved.
 * See LICENSE for license details.
 */

declare(strict_types=1);

/**
 * Magento configuration XML DOM utility
 */
namespace Lingaro\Magento2Codegen\Util\Magento\Config;

use DOMAttr;
use DOMCdataSection;
use DOMDocument;
use DOMElement;
use DOMNode;
use DOMText;
use DOMXPath;
use Exception;
use InvalidArgumentException;
use LibXMLError;
use RuntimeException;
use function count;
use function implode;
use function is_array;
use function libxml_get_errors;
use function libxml_use_internal_errors;
use function preg_match_all;
use function sprintf;
use function str_replace;
use function strpos;
use function trim;

/**
 * Class Dom
 *
 * @SuppressWarnings(PHPMD.CouplingBetweenObjects)
 * @SuppressWarnings(PHPMD.ExcessiveClassComplexity)
 * @api
 * @since 100.0.2
 */
class Dom
{
    /**
     * Prefix which will be used for root namespace
     */
    private const ROOT_NAMESPACE_PREFIX = 'x';

    /**
     * Format of items in errors array to be used by default. Available placeholders - fields of \LibXMLError.
     */
    private const ERROR_FORMAT_DEFAULT = "%message%\nLine: %line%\n";

    protected DOMDocument $dom;
    protected Dom\NodeMergingConfig $nodeMergingConfig;

    /**
     * Name of attribute that specifies type of argument node
     */
    protected ?string $typeAttributeName;

    /**
     * Schema validation file
     */
    protected ?string $schema;

    /**
     * Format of error messages
     */
    protected string $errorFormat;

    /**
     * Default namespace for xml elements
     */
    protected ?string $rootNamespace;

    /**
     * Build DOM with initial XML contents and specifying identifier attributes for merging
     *
     * Format of $idAttributes: array('/xpath/to/some/node' => 'id_attribute_name')
     * The path to ID attribute name should not include any attribute notations or modifiers -- only node names
     */
    public function __construct(
        string $xml,
        array $idAttributes = [],
        ?string $typeAttributeName = null,
        ?string $schemaFile = null,
        string $errorFormat = self::ERROR_FORMAT_DEFAULT
    ) {
        $this->schema = $schemaFile;
        $this->nodeMergingConfig = new Dom\NodeMergingConfig(new Dom\NodePathMatcher(), $idAttributes);
        $this->typeAttributeName = $typeAttributeName;
        $this->errorFormat = $errorFormat;
        $this->dom = $this->initDom($xml);
        $this->rootNamespace = $this->dom->lookupNamespaceUri($this->dom->namespaceURI);
    }

    /**
     * Retrieve array of xml errors
     *
     * @return string[]
     */
    private static function getXmlErrors($errorFormat): array
    {
        $validationErrors = libxml_get_errors();
        if (count($validationErrors)) {
            $errors = [];
            foreach ($validationErrors as $error) {
                $errors[] = self::renderErrorMessage($error, $errorFormat);
            }
            return $errors;
        }
        return ['Unknown validation error'];
    }

    /**
     * Merge $xml into DOM document
     */
    public function merge(string $xml): void
    {
        $dom = $this->initDom($xml);
        $this->mergeNode($dom->documentElement, '');
    }

    /**
     * Recursive merging of the \DOMElement into the original document
     *
     * Algorithm:
     * 1. Find the same node in original document
     * 2. Extend and override original document node attributes and scalar value if found
     * 3. Append new node if original document doesn't have the same node
     *
     * @SuppressWarnings(PHPMD.CyclomaticComplexity)
     * @SuppressWarnings(PHPMD.NPathComplexity)
     */
    protected function mergeNode(DOMElement $node, string $parentPath): void
    {
        $path = $this->getNodePathByParent($node, $parentPath);

        $matchedNode = $this->getMatchedNode($path);

        /* Update matched node attributes and value */
        if ($matchedNode) {
            //different node type
            if (
                $this->typeAttributeName &&
                $node->hasAttribute($this->typeAttributeName) &&
                $matchedNode->hasAttribute($this->typeAttributeName) &&
                $node->getAttribute($this->typeAttributeName) !== $matchedNode->getAttribute($this->typeAttributeName)
            ) {
                $parentMatchedNode = $this->getMatchedNode($parentPath);
                $newNode = $this->dom->importNode($node, true);
                $parentMatchedNode->replaceChild($newNode, $matchedNode);
                return;
            }

            $this->mergeAttributes($matchedNode, $node);
            if (!$node->hasChildNodes()) {
                return;
            }
            /* override node value */
            if ($this->isTextNode($node)) {
                /* skip the case when the matched node has children, otherwise they get overridden */
                if (
                    !$matchedNode->hasChildNodes()
                    || $this->isTextNode($matchedNode)
                    || $this->isCdataNode($matchedNode)
                ) {
                    $matchedNode->nodeValue = $node->childNodes->item(0)->nodeValue;
                }
                return;
            } elseif ($this->isCdataNode($node) && $this->isTextNode($matchedNode)) {
                /* Replace text node with CDATA section */
                if ($this->findCdataSection($node)) {
                    $matchedNode->nodeValue = $this->findCdataSection($node)->nodeValue;
                }
                return;
            } elseif ($this->isCdataNode($node) && $this->isCdataNode($matchedNode)) {
                /* Replace CDATA with new one */
                $this->replaceCdataNode($matchedNode, $node);
                return;
            }
            /* recursive merge for all child nodes */
            foreach ($node->childNodes as $childNode) {
                if ($childNode instanceof DOMElement) {
                    $this->mergeNode($childNode, $path);
                }
            }
            return;
        }
        /* Add node as is to the document under the same parent element */
        $parentMatchedNode = $this->getMatchedNode($parentPath);
        $newNode = $this->dom->importNode($node, true);
        $parentMatchedNode->appendChild($newNode);
    }

    /**
     * Check if the node content is text
     */
    protected function isTextNode(DOMNode $node): bool
    {
        return $node->childNodes->length == 1 && $node->childNodes->item(0) instanceof DOMText;
    }

    /**
     * Check if the node content is CDATA (probably surrounded with text nodes) or just text node
     */
    private function isCdataNode(DOMNode $node): bool
    {
        // If every child node of current is NOT \DOMElement
        // It is arbitrary combination of text nodes and CDATA sections.
        foreach ($node->childNodes as $childNode) {
            if ($childNode instanceof DOMElement) {
                return false;
            }
        }

        return true;
    }

    /**
     * Finds CDATA section from given node children
     */
    private function findCdataSection($node): ?DOMCdataSection
    {
        foreach ($node->childNodes as $childNode) {
            if ($childNode instanceof DOMCdataSection) {
                return $childNode;
            }
        }
        return null;
    }

    /**
     * Replaces CDATA section in $oldNode with $newNode's
     */
    private function replaceCdataNode(DOMNode $oldNode, DOMNode $newNode): void
    {
        $oldCdata = $this->findCdataSection($oldNode);
        $newCdata = $this->findCdataSection($newNode);

        if ($oldCdata && $newCdata) {
            $oldCdata->nodeValue = $newCdata->nodeValue;
        }
    }

    /**
     * Merges attributes of the merge node to the base node
     */
    protected function mergeAttributes(DOMNode $baseNode, DOMNode $mergeNode): void
    {
        foreach ($mergeNode->attributes as $attribute) {
            $baseNode->setAttribute($this->getAttributeName($attribute), $attribute->value);
        }
    }

    /**
     * Identify node path based on parent path and node attributes
     */
    protected function getNodePathByParent(DOMElement $node, string $parentPath): string
    {
        $prefix = $this->rootNamespace === null ? '' : self::ROOT_NAMESPACE_PREFIX . ':';
        $path = $parentPath . '/' . $prefix . $node->tagName;
        $idAttribute = $this->nodeMergingConfig->getIdAttribute($path);
        if (is_array($idAttribute)) {
            $constraints = [];
            foreach ($idAttribute as $attribute) {
                $value = $node->getAttribute($attribute);
                $constraints[] = "@{$attribute}='{$value}'";
            }
            $path .= '[' . implode(' and ', $constraints) . ']';
        } elseif ($idAttribute && (strlen($node->getAttribute($idAttribute)) > 0)) {
            $value = $node->getAttribute($idAttribute);
            $path .= "[@{$idAttribute}='{$value}']";
        }
        return $path;
    }

    /**
     * Getter for node by path
     *
     * @throws Exception An exception is possible if original document contains multiple nodes for identifier
     */
    protected function getMatchedNode(string $nodePath): ?DOMNode
    {
        $xPath = new DOMXPath($this->dom);
        if ($this->rootNamespace) {
            $xPath->registerNamespace(self::ROOT_NAMESPACE_PREFIX, $this->rootNamespace);
        }
        $matchedNodes = $xPath->query($nodePath);
        $node = null;
        if ($matchedNodes->length > 1) {
            throw new RuntimeException(sprintf(
                "More than one node matching the query: %s, Xml is: %s",
                $nodePath,
                $this->dom->saveXML()
            ));
        } elseif ($matchedNodes->length == 1) {
            $node = $matchedNodes->item(0);
        }
        return $node;
    }

    /**
     * Render error message string by replacing placeholders '%field%' with properties of \LibXMLError
     *
     * @throws InvalidArgumentException
     */
    private static function renderErrorMessage(LibXMLError $errorInfo, string $format): string
    {
        $result = $format;
        foreach ($errorInfo as $field => $value) {
            $placeholder = '%' . $field . '%';
            $value = trim((string)$value);
            $result = str_replace($placeholder, $value, $result);
        }
        if (strpos($result, '%') !== false) {
            if (preg_match_all('/%.+%/', $result, $matches)) {
                $unsupported = [];
                foreach ($matches[0] as $placeholder) {
                    if (strpos($result, $placeholder) !== false) {
                        $unsupported[] = $placeholder;
                    }
                }
                if (!empty($unsupported)) {
                    throw new InvalidArgumentException(
                        "Error format '{$format}' contains unsupported placeholders: " . implode(', ', $unsupported)
                    );
                }
            }
        }
        return $result;
    }

    public function getDom(): DOMDocument
    {
        return $this->dom;
    }

    /**
     * Create DOM document based on $xml parameter
     *
     * @throws Dom\ValidationException
     */
    protected function initDom(string $xml): DOMDocument
    {
        $dom = new DOMDocument();
        $useErrors = libxml_use_internal_errors(true);
        $res = $dom->loadXML($xml);
        if (!$res) {
            $errors = self::getXmlErrors($this->errorFormat);
            libxml_use_internal_errors($useErrors);
            throw new Dom\ValidationException(implode("\n", $errors));
        }
        libxml_use_internal_errors($useErrors);
        return $dom;
    }

    /**
     * Returns the attribute name with prefix, if there is one
     */
    private function getAttributeName(DOMAttr $attribute): string
    {
        if ($attribute->prefix !== null && !empty($attribute->prefix)) {
            return $attribute->prefix . ':' . $attribute->name;
        }
        return $attribute->name;
    }
}
