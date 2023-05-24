<?php

/**
 * Copyright © 2023 Lingaro sp. z o.o. All rights reserved.
 * See LICENSE for license details.
 */

declare(strict_types=1);

namespace Lingaro\Magento2Codegen\Service\FileMerger;

interface MergerInterface
{
    public function setParams(array $params): void;

    public function merge(string $oldContent, string $newContent): string;

    public function isExperimental(): bool;
}
