<?php

namespace Orba\Magento2Codegen\Service\FileMerger\PhpMerger\NodeTree;

use PhpParser\Node;

class Root
{
    /** @var string */
    private $_name = 'root', $_id;

    /** @var array */
    private $_nodes = [];

    /** @var Node */
    private $_lastNode;

    /** @var self */
    private $_up;

    /** @var self */
    private $_down;

    public function __construct()
    {
        $this->_id = uniqid();
    }

    public function setName(string $name): self
    {
        $this->_name = $name;
        return $this;
    }

    public function getName()
    {
        return $this->_name;
    }

    public function addNode(Node $node): self
    {
        if (!in_array($node, $this->_nodes)) {
            $this->_nodes[] = $node;
        }
        return $this;
    }

    public function setNode(Node $node): self
    {
        $this->_lastNode = $node;
        return $this;
    }

    public function getLastNode()
    {
        return $this->_lastNode;
    }

    public function getRootName(): string
    {
        if (!$this->_up) {
            return $this->getName();
        }
        return $this->_up->getRootName() . "-" . $this->_name;
    }

    public function isValid()
    {
        $name = $this->getRootName();
        return !(bool)strpos($name, 'XXX');
    }

    public function getFirstValidBranch()
    {
        if (!$this->isValid()) {
            $up = $this->up();
            return $up->getFirstValidBranch();
        }
        return $this;
    }

    public function up(self $up = null): ?self
    {
        if ($up instanceof self) {
            $this->_up = $up;
            return $this;
        }
        if ($this->_up instanceof self) {
            return $this->_up;
        }
        return null;
    }

    public function down(): self
    {
        if (!$this->_down instanceof self) {
            $this->_down = self::create()->up($this);
        }
        return $this->_down;
    }

    public static function create(): self
    {
        return new self();
    }
}