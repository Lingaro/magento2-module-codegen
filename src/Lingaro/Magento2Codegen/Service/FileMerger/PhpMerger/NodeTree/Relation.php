<?php

/**
 * Copyright © 2023 Lingaro sp. z o.o. All rights reserved.
 * See LICENSE for license details.
 */

declare(strict_types=1);

namespace Lingaro\Magento2Codegen\Service\FileMerger\PhpMerger\NodeTree;

use PhpParser\Node;

class Relation
{
    private ?Node $parent = null;
    private string $param;

    public function setParent(Node $node = null): self
    {
        $this->parent = $node;
        return $this;
    }

    public function getParent(): ?Node
    {
        return $this->parent;
    }

    public function setParam(string $param): self
    {
        $this->param = $param;
        return $this;
    }

    public function getParam(): string
    {
        return $this->param;
    }
}
