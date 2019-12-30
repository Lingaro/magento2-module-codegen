<?php

namespace Orba\Magento2Codegen\Service\FileMerger\PhpParser;

use PhpParser\Node;

class NodeWrapper extends Node\Stmt
{
    /** @var Node\Stmt[]|null Statements */
    public $stmts;

    /** @var string */
    public $name = 'wrapper';

    /** @var string */
    public $id = '';

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
        $this->id = uniqid();
    }

    public function getSubNodeNames(): array
    {
        return ['stmts'];
    }

    public function getType(): string
    {
        return 'Orba_Stmt_Wrapper';
    }
}