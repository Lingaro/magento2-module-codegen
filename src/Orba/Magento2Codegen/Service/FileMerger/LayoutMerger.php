<?php
/**
 * @package Orba\Magento2Codegen\Service\FileMerger
 * @copyright Copyright (c) Orba Sp. z o.o. (http://orba.co)
 */

namespace Orba\Magento2Codegen\Service\FileMerger;

use Magento\Framework\View\Layout\Element;

class LayoutMerger extends AbstractMerger implements MergerInterface
{
    public function merge(string $oldContent, string $newContent): string
    {
        $layoutStr = '';
        $useErrors = libxml_use_internal_errors(true);
        foreach ([$oldContent, $newContent] as $content) {
            /** @var $fileXml Element */
            $fileXml = $this->_loadXmlString($content);
            if (!$fileXml instanceof Element) {
                $xmlErrors = $this->getXmlErrors(libxml_get_errors());
//                $this->_logXmlErrors($file->getFilename(), $xmlErrors);
                libxml_clear_errors();
                continue;
            }
//            $handleName = basename($file->getFilename(), '.xml');
            $tagName = $fileXml->getName() === 'layout' ? 'layout' : 'handle';
            $handleAttributes = ' id="' . '$handleName' . '"' . $this->_renderXmlAttributes($fileXml);
            $handleStr = '<' . $tagName . $handleAttributes . '>' . $fileXml->innerXml() . '</' . $tagName . '>';
            $layoutStr .= $handleStr;
        }
        libxml_use_internal_errors($useErrors);
        $layoutStr = '<layouts xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance">' . $layoutStr . '</layouts>';
        return $layoutStr;
    }

    /**
     * Return object representation of XML string
     *
     * @param string $xmlString
     * @return \SimpleXMLElement
     */
    protected function _loadXmlString($xmlString)
    {
        return simplexml_load_string($xmlString, Element::class);
    }

    /**
     * Get formatted xml errors
     *
     * @param array $libXmlErrors
     * @return array
     */
    private function getXmlErrors($libXmlErrors)
    {
        $errors = [];
        if (count($libXmlErrors)) {
            foreach ($libXmlErrors as $error) {
                $errors[] = "{$error->message} Line: {$error->line}";
            }
        }
        return $errors;
    }

    /**
     * Log xml errors to system log
     *
     * @param string $fileName
     * @param array $xmlErrors
     * @return void
     */
    protected function _logXmlErrors($fileName, $xmlErrors)
    {
//        $this->logger->info(
//            sprintf("Theme layout update file '%s' is not valid.\n%s", $fileName, implode("\n", $xmlErrors))
//        );
    }

    /**
     * Return attributes of XML node rendered as a string
     *
     * @param \SimpleXMLElement $node
     * @return string
     */
    protected function _renderXmlAttributes(\SimpleXMLElement $node)
    {
        $result = '';
        foreach ($node->attributes() as $attributeName => $attributeValue) {
            $result .= ' ' . $attributeName . '="' . $attributeValue . '"';
        }
        return $result;
    }
}