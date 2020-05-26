<?php

namespace Orba\Magento2Codegen\Service\FileMerger\PhpMerger\NodeTree;

use PhpParser\Node;

/**
 * Class Relation
 * @package Orba\Magento2Codegen\Service\FileMerger\PhpMerger\NodeTree
 */
class Relation
{
    /** @var Node */
    private $_parent;

    /** @var string */
    private $_param;

    /**
     * @param Node|null $node
     * @return $this
     */
    public function setParent(Node $node = null): self
    {
        $this->_parent = $node;
        return $this;
    }

    /**
     * @return Node
     */
    public function getParent(): ?Node
    {
        return $this->_parent;
    }

    /**
     * @param string $param
     * @return $this
     */
    public function setParam(string $param): self
    {
        $this->_param = $param;
        return $this;
    }

    /**
     * @return string
     */
    public function getParam(): string
    {
        return $this->_param;
    }
}