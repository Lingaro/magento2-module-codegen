<?php

/**
 * Copyright © 2023 Lingaro sp. z o.o. All rights reserved.
 * See LICENSE for license details.
 */

declare(strict_types=1);

namespace Lingaro\Magento2Codegen\Service\FileMerger\PhpMerger;

use Lingaro\Magento2Codegen\Service\FileMerger\PhpMerger\NodeTree\RootFactory;

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
