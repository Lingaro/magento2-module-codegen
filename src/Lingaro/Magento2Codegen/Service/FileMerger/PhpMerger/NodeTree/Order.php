<?php

/**
 * Copyright © 2023 Lingaro sp. z o.o. All rights reserved.
 * See LICENSE for license details.
 */

declare(strict_types=1);

namespace Lingaro\Magento2Codegen\Service\FileMerger\PhpMerger\NodeTree;

use PhpParser\Node\Stmt\Class_;
use PhpParser\Node\Stmt\ClassConst;
use PhpParser\Node\Stmt\ClassMethod;
use PhpParser\Node\Stmt\Const_;
use PhpParser\Node\Stmt\Namespace_;
use PhpParser\Node\Stmt\Property;
use PhpParser\Node\Stmt\TraitUse;
use PhpParser\Node\Stmt\Use_;
use PhpParser\Node\Stmt\Declare_;

use function array_merge;
use function array_values;
use function get_class;

class Order
{
    private const ORDER = [
        Declare_::class,
        Namespace_::class,
        Use_::class,
        Const_::class,
        Class_::class,
        TraitUse::class,
        ClassConst::class,
        Property::class,
        ClassMethod::class
    ];

    private array $orderTable = [];

    public function __construct()
    {
        foreach (self::ORDER as $class) {
            $this->orderTable[$class] = [];
        }
    }

    public function sort(array $nodes): array
    {
        $orderTable = $this->orderTable;
        foreach ($nodes as $one) {
            $class = get_class($one);
            $orderTable[$class][] = $one;
        }
        return array_merge(...array_values($orderTable));
    }
}
