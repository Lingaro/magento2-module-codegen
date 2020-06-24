<?php

namespace Orba\Magento2Codegen\Service\FileMerger\PhpMerger;

use Orba\Magento2Codegen\Service\FileMerger\PhpMerger\NodeTree;
use Orba\Magento2Codegen\Service\FileMerger\PhpMerger\NodeTree\RootFactory;

class NodeTreeFactory
{
    /** @var RootFactory */
    private $rootFactory;

    /**
     * NodeTreeFactory constructor.
     * @param RootFactory $rootFactory
     */
    public function __construct(RootFactory $rootFactory)
    {
        $this->rootFactory = $rootFactory;
    }

    /**
     * @return \Orba\Magento2Codegen\Service\FileMerger\PhpMerger\NodeTree
     */
    public function create(): NodeTree
    {
        return new NodeTree($this->rootFactory);
    }
}