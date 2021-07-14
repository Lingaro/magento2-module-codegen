<?php

/**
 * @copyright Copyright © 2021 Orba. All rights reserved.
 * @author    info@orba.co
 */

declare(strict_types=1);

namespace Orba\Magento2Codegen\Service\FileMerger\PhpMerger;

use Orba\Magento2Codegen\Service\FileMerger\PhpMerger\NodeTree\RootFactory;

class NodeTreeFactory
{
    private RootFactory $rootFactory;

    public function __construct(RootFactory $rootFactory)
    {
        $this->rootFactory = $rootFactory;
    }

    public function create(): NodeTree
    {
        return new NodeTree($this->rootFactory);
    }
}
