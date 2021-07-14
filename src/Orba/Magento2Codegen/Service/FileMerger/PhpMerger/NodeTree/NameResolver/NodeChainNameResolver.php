<?php

/**
 * @copyright Copyright © 2021 Orba. All rights reserved.
 * @author    info@orba.co
 */

declare(strict_types=1);

namespace Orba\Magento2Codegen\Service\FileMerger\PhpMerger\NodeTree\NameResolver;

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
