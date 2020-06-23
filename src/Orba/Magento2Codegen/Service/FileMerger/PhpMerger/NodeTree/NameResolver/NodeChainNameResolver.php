<?php


namespace Orba\Magento2Codegen\Service\FileMerger\PhpMerger\NodeTree\NameResolver;

use PhpParser\Node;

/**
 * Interface NodeChainNameResolver
 * @package Orba\Magento2Codegen\Service\FileMerger\PhpMerger\NodeTree\NameResolver
 */
class NodeChainNameResolver implements NameResolverInterface
{

    /**
     * @inheritDoc
     */
    public function resolve(Node $node): string
    {
        $name = '';
        $serializedNode = $node->jsonSerialize();
        foreach ($serializedNode as $row) {
            if (is_array($row)) {
                foreach($row as $element) {
                    if($element instanceof Node) {
                        $name .= $this->resolve($element);
                    }
                }
                continue;
            }
            if (is_string($row)) {
                $name .= $row;
            }
            if ($row instanceof Node) {
                $name .= $this->resolve($row);
            }
        }
        return $name;
    }
}