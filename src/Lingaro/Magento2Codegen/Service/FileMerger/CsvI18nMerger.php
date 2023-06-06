<?php

/**
 * Copyright © 2023 Lingaro sp. z o.o. All rights reserved.
 * See LICENSE for license details.
 */

declare(strict_types=1);

namespace Lingaro\Magento2Codegen\Service\FileMerger;

use Lingaro\Magento2Codegen\Service\FileMerger\CsvI18nMerger\Processor;

class CsvI18nMerger extends AbstractMerger implements MergerInterface
{
    private Processor $processor;

    public function __construct(Processor $processor)
    {
        $this->processor = $processor;
    }

    public function merge(string $oldContent, string $newContent): string
    {
        return $this->processor->generateContent($this->processor->merge($oldContent, $newContent));
    }
}
