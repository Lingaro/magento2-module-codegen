<?php

/**
 * @copyright Copyright © 2021 Orba. All rights reserved.
 * @author    info@orba.co
 */

declare(strict_types=1);

namespace Orba\Magento2Codegen\Service\FileMerger\PhpMerger\NodeTree\NameResolver;

use PhpParser\Node;

interface NameResolverInterface
{
    /**
     * Returns full node name
     */
    public function resolve(Node $node): string;
}
