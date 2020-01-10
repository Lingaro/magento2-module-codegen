<?php

namespace Orba\Magento2Codegen\Service\FileMerger\PhpMerger\NodeTree;

use PhpParser\Node;

class Relation
{
    /** @var Node */
    private $_parent, $_child;

    /** @var string */
    private $_param;

    public function setParent(Node $node = null)
    {

        $this->_parent = $node;
        return $this;
    }

    public function getParent()
    {
        return $this->_parent;
    }

    public function setChild(Node $node)
    {
        $this->_child = $node;
        return $this;
    }

    public function getChild()
    {
        return $this->_child;
    }

    public function setParam(string $param)
    {
        $this->_param = $param;
        return $this;
    }

    public function getParam()
    {
        return $this->_param;
    }

    public static function create(): self
    {
        return new self();
    }
}