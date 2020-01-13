<?php


namespace Orba\Magento2Codegen\Service\FileMerger\PhpMerger\NodeTree;

use PhpParser\Node\Stmt\Class_;
use PhpParser\Node\Stmt\ClassConst;
use PhpParser\Node\Stmt\ClassMethod;
use PhpParser\Node\Stmt\Const_;
use PhpParser\Node\Stmt\Namespace_;
use PhpParser\Node\Stmt\Property;
use PhpParser\Node\Stmt\TraitUse;
use PhpParser\Node\Stmt\Use_;

class Order
{
    const ORDER = [
        Namespace_::class,
        Use_::class,
        Const_::class,
        Class_::class,
        TraitUse::class,
        Property::class,
        ClassConst::class,
        ClassMethod::class
    ];

    private $_orderTable = [];

    /**
     * Order constructor.
     */
    public function __construct()
    {
        foreach (self::ORDER as $class) {
            $this->_orderTable[$class] = [];
        }
    }

    /**
     * @param $nodes
     * @return array
     */
    public function sort(array $nodes): array
    {
        $orderTable = $this->_orderTable;
        foreach ($nodes as $one) {
            $class = get_class($one);
            $orderTable[$class][] = $one;
        }
        return array_merge(...array_values($orderTable));
    }
}