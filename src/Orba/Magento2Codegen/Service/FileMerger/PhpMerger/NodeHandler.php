<?php

namespace Orba\Magento2Codegen\Service\FileMerger\PhpMerger;

use Orba\Magento2Codegen\Service\FileMerger\PhpMerger\Node\AbstractHandler;
use Orba\Magento2Codegen\Service\FileMerger\PhpMerger\NodeTree\Group;
use Orba\Magento2Codegen\Service\FileMerger\PhpParser\NodeWrapper;
use PhpParser\Node;

class NodeHandler
{
    /** @var NodeTree */
    private $_tree;

    /** @var array */
    private $_handlers = [];

    public function __construct(

    ) {
        $this->_handlers = [

        ];
    }
}