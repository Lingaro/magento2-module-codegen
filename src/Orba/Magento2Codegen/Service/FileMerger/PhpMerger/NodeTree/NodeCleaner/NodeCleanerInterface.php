<?php


namespace Orba\Magento2Codegen\Service\FileMerger\PhpMerger\NodeTree\NodeCleaner;

use PhpParser\Node;

/**
 * Interface NodeCleanerInterface
 * @package Orba\Magento2Codegen\Service\FileMerger\PhpMerger\NodeTree\Cleaners
 */
interface NodeCleanerInterface
{
    /**
     * Returns node class of node that can be handled by this cleaner
     * @return string
     */
    public function getCode(): string;

    /**
     * Accepts and clean similar nodes
     * @param array|Node[] $nodes
     * @return array
     */
    public function clean(array $nodes): array;
}