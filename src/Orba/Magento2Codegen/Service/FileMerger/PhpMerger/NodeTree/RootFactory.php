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

    /**
     * RootFactory constructor.
     * @param RelationFactory $relationFactory
     * @param Order $order
     */
    public function __construct(RelationFactory $relationFactory, Order $order)
    {
        $this->_relationFactory = $relationFactory;
        $this->_order = $order;
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
            $name
        );
    }
}