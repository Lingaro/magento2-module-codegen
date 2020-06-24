<?php

namespace Orba\Magento2Codegen\Service\FileMerger\PhpMerger\NodeTree;

/**
 * Class RootFactory
 * @package Orba\Magento2Codegen\Service\FileMerger\PhpMerger\NodeTree
 */
class RootFactory
{
    /** @var RelationFactory */
    private $_relationFactory;

    /** @var Order */
    private $_order;

    /** @var NodeCleaner */
    private $_nodeCleaner;

    /**
     * RootFactory constructor.
     * @param RelationFactory $relationFactory
     * @param Order $order
     * @param NodeCleaner $nodeCleaner
     */
    public function __construct(
        RelationFactory $relationFactory,
        Order $order,
        NodeCleaner $nodeCleaner
    )
    {
        $this->_relationFactory = $relationFactory;
        $this->_order = $order;
        $this->_nodeCleaner = $nodeCleaner;
    }

    /**
     * @param string|null $name
     * @return Root
     */
    public function create(string $name = null): Root
    {
        return new Root(
            $this,
            $this->_relationFactory,
            $this->_order,
            $this->_nodeCleaner,
            $name
        );
    }
}