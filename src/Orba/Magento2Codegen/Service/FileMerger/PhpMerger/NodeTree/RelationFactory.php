<?php

namespace Orba\Magento2Codegen\Service\FileMerger\PhpMerger\NodeTree;

/**
 * Class Relation
 * @package Orba\Magento2Codegen\Service\FileMerger\PhpMerger\NodeTree
 */
class RelationFactory
{
    public function create(): Relation
    {
        return new Relation();
    }
}