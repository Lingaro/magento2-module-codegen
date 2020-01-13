<?php

namespace Orba\Magento2Codegen\Service\FileMerger\PhpParser;

use PhpParser\Node;

/**
 * Class NodeWrapper
 * @package Orba\Magento2Codegen\Service\FileMerger\PhpParser
 */
class NodeWrapper extends Node\Stmt
{
    /** @var Node\Stmt[]|null Statements */
    public $stmts;

    /** @var string */
    public $name = 'wrapper';

    /** @var string */
    public $id = '';

    /** @var bool */
    private $_wrapped = false;

    /**
     * NodeWrapper constructor.
     * @param array $attributes
     */
    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
        $this->id = uniqid();
    }

    /**
     * @param mixed ...$parsedNodes
     * @return $this
     */
    public function wrap(...$parsedNodes): self
    {
        $this->stmts = array_merge(...$parsedNodes);
        $this->_wrapped = true;
        return $this;
    }

    /**
     * @return array
     */
    public function unwrap(): array
    {
        return $this->stmts;
    }

    /**
     * @return bool
     */
    public function isWrapped(): bool
    {
        return $this->_wrapped;
    }

    /**
     * @return array
     */
    public function getSubNodeNames(): array
    {
        return ['stmts'];
    }

    /**
     * @return string
     */
    public function getType(): string
    {
        return 'Orba_Stmt_Wrapper';
    }
}