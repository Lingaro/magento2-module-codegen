<?php

/**
 * Copyright © 2023 Lingaro sp. z o.o. All rights reserved.
 * See LICENSE for license details.
 */

declare(strict_types=1);

namespace Lingaro\Magento2Codegen\Service\FileMerger\PhpMerger\NodeTree\NameResolver;

use PhpParser\Node;

use function is_array;
use function is_string;

class NodeChainNameResolver implements NameResolverInterface
{
    public function resolve(Node $node): string
    {
        $name = '';
        $serializedNode = $node->jsonSerialize();
        foreach ($serializedNode as $row) {
            if (is_array($row)) {
                foreach ($row as $element) {
                    if ($element instanceof Node) {
                        $name .= $this->resolve($element);
                    }
                }
                continue;
            }
            if (is_string($row)) {
                $name .= $row;
            }
            if ($row instanceof Node) {
                $name .= $this->resolve($row);
            }
        }
        return $name;
    }
}
