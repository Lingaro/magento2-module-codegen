<?php

namespace Orba\Magento2Codegen\Service\Twig;

use LogicException;
use Twig\Environment;
use Twig\Node\Expression\AssignNameExpression;
use Twig\Node\Expression\NameExpression;
use Twig\Node\ForNode;
use Twig\Node\IfNode;
use Twig\Node\ModuleNode;
use Twig\Node\Node;

/**
 * @see https://stackoverflow.com/a/57758048
 */
class TwigToSchema
{
    public function infer(Environment $twig, string $twigTemplateName): array
    {
        $source = $twig->getLoader()->getSourceContext($twigTemplateName);
        $tokens = $twig->tokenize($source);
        $ast = $twig->parse($tokens);
        return $this->inferFromAst($ast);
    }

    public function inferFromAst(ModuleNode $ast): array
    {
        $keys = $this->visit($ast);
        foreach ($keys as $key => $value) {
            if ($value['always_defined'] || $key === '_self') {
                unset($keys[$key]);
            }
        }
        return $keys;
    }

    private function visit(Node $ast): array
    {
        $vars = [];
        switch (get_class($ast)) {
            case AssignNameExpression::class:
            case NameExpression::class:
                $vars[$ast->getAttribute('name')] = [
                    'always_defined' => $ast->getAttribute('always_defined'),
                    'type' => 'scalar'
                ];
                break;
            case ForNode::class:
                $seqNode = $ast->getNode('seq');
                $seqName = $seqNode->hasAttribute('name') ? $seqNode->getAttribute('name') : null;
                $valueNode = $ast->getNode('value_target');
                $valueName = $valueNode->hasAttribute('name') ? $valueNode->getAttribute('name') : null;
                foreach ($ast as $key => $node) {
                    /** @var Node $node */
                    switch ($key) {
                        case 'body':
                            $vars = array_merge($vars, $this->visit($node));
                            if ($seqName && $valueName) {
                                $vars[$seqName] = [
                                    'always_defined' => false,
                                    'type' => 'array',
                                    'elements' => $this->visitForNode($node, $ast, $valueName)
                                ];
                            }
                            break;
                        default:
                            break;
                    }
                }
                break;
            case IfNode::class:
                foreach ($ast->getNode('tests') as $key => $test) {
                    $vars = array_merge($vars, $this->visit($test));
                }
                try {
                    foreach ($ast->getNode('else') as $key => $else) {
                        $vars = array_merge($vars, $this->visit($else));
                    }
                } catch (LogicException $e) {}
                break;
            default:
                if ($ast->count()) {
                    foreach ($ast as $key => $node) {
                        $vars = array_merge($vars, $this->visit($node));
                    }
                }
                break;
        }
        return $vars;
    }

    private function visitForNode(Node $currentNode, Node $parentNode, string $name): array
    {
        $attributes = [];
        if (get_class($currentNode) === NameExpression::class && $currentNode->getAttribute('name') === $name) {
            $attributes[] = $parentNode->getNode('attribute')->getAttribute('value');
        }
        if ($currentNode->count()) {
            foreach ($currentNode as $key => $node) {
                $attributes = array_merge($attributes, $this->visitForNode($node, $currentNode, $name));
            }
        }
        return $attributes;
    }
}