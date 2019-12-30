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
                    'always_defined' => $ast->getAttribute('always_defined')
                ];
                break;
            case ForNode::class:
                foreach ($ast as $key => $node) {
                    /** @var Node $node */
                    switch ($key) {
//                        case 'value_target':
//                            $vars[$node->getAttribute('name')] = [
//                                'for_loop_target' => true,
//                                'always_defined' => $node->getAttribute('always_defined')
//                            ];
//                            break;
                        case 'seq':
                            try {
                                $vars[$node->getAttribute('name')] = [
                                    'always_defined' => $node->getAttribute('always_defined')
                                ];
                            } catch (LogicException $e) {}
                            break;
                        case 'body':
                            $vars = array_merge($vars, $this->visit($node));
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
}