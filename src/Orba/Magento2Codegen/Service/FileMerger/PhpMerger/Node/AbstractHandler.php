<?php

namespace Orba\Magento2Codegen\Service\FileMerger\PhpMerger\Node;

use Orba\Magento2Codegen\Service\FileMerger\PhpParser\ParentWrapper;
use PhpParser\Node;

abstract class AbstractHandler
{
    public $class = '';

    protected $nodes = [];


}