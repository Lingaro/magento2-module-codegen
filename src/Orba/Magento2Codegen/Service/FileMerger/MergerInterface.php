<?php

/**
 * @copyright Copyright © 2021 Orba. All rights reserved.
 * @author    info@orba.co
 */

declare(strict_types=1);

namespace Orba\Magento2Codegen\Service\FileMerger;

interface MergerInterface
{
    public function setParams(array $params): void;

    public function merge(string $oldContent, string $newContent): string;

    public function isExperimental(): bool;
}
