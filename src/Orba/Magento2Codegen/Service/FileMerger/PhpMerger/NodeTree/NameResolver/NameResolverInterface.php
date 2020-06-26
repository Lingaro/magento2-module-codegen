<?php


namespace Orba\Magento2Codegen\Service\FileMerger\PhpMerger\NodeTree\NameResolver;

use JsonSerializable;
use PhpParser\Node;

/**
 * Interface NameResolverInterface
 * @package Orba\Magento2Codegen\Service\FileMerger\PhpMerger\NodeTree\NameResolver
 */
interface NameResolverInterface
{
    /**
     * Returns full node name
     * @param Node|JsonSerializable $node
     * @return string
     */
    public function resolve(Node $node): string;
}