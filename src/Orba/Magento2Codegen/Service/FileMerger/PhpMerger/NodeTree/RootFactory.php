<?php

/**
 * @copyright Copyright © 2021 Orba. All rights reserved.
 * @author    info@orba.co
 */

declare(strict_types=1);

namespace Orba\Magento2Codegen\Service\FileMerger\PhpMerger\NodeTree;

class RootFactory
{
    private RelationFactory $relationFactory;
    private Order $order;
    private NodeCleaner $nodeCleaner;

    public function __construct(RelationFactory $relationFactory, Order $order, NodeCleaner $nodeCleaner)
    {
        $this->relationFactory = $relationFactory;
        $this->order = $order;
        $this->nodeCleaner = $nodeCleaner;
    }

    public function create(?string $name = null): Root
    {
        return new Root(
            $this,
            $this->relationFactory,
            $this->order,
            $this->nodeCleaner,
            $name
        );
    }
}
