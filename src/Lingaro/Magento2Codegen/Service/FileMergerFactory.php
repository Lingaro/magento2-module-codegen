<?php

/**
 * Copyright © 2023 Lingaro sp. z o.o. All rights reserved.
 * See LICENSE for license details.
 */

declare(strict_types=1);

namespace Lingaro\Magento2Codegen\Service;

use Lingaro\Magento2Codegen\Service\FileMerger\MergerInterface;

use function preg_match;

class FileMergerFactory
{
    /**
     * @var MergerInterface[]
     */
    private array $mergers = [];

    public function addMerger(string $fileNameRegex, MergerInterface $merger, array $params = []): void
    {
        $merger->setParams($params);
        $this->mergers[$fileNameRegex] = $merger;
    }

    public function create(string $fileName): ?MergerInterface
    {
        foreach ($this->mergers as $fileNameRegex => $merger) {
            if (preg_match($fileNameRegex, $fileName)) {
                return $merger;
            }
        }
        return null;
    }
}
