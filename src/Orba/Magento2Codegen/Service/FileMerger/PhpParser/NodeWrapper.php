<?php

/**
 * Copyright © 2023 Lingaro sp. z o.o. All rights reserved.
 * See LICENSE for license details.
 */

declare(strict_types=1);

namespace Orba\Magento2Codegen\Service\FileMerger\PhpParser;

use PhpParser\Node;

class NodeWrapper extends Node\Stmt
{
    /**
     * @var Node\Stmt[]|null
     */
    public ?array $stmts;

    public string $name = 'wrapper';
    public string $id = '';
    private bool $wrapped = false;

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
        $this->id = uniqid();
    }

    public function wrap(...$parsedNodes): self
    {
        $this->stmts = array_merge(...$parsedNodes);
        $this->wrapped = true;
        return $this;
    }

    public function unwrap(): array
    {
        return $this->stmts;
    }

    public function isWrapped(): bool
    {
        return $this->wrapped;
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
