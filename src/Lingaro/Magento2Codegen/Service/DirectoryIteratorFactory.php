<?php

/**
 * Copyright © 2023 Lingaro sp. z o.o. All rights reserved.
 * See LICENSE for license details.
 */

declare(strict_types=1);

namespace Lingaro\Magento2Codegen\Service;

use RecursiveDirectoryIterator;
use UnexpectedValueException;

class DirectoryIteratorFactory
{
    public function create(string $dir): RecursiveDirectoryIterator
    {
        try {
            $directoryIterator = new RecursiveDirectoryIterator($dir);
        } catch (UnexpectedValueException $e) {
            throw new UnexpectedValueException("Directory not found: " . $dir, 0, $e);
        }
        $directoryIterator->setFlags(RecursiveDirectoryIterator::SKIP_DOTS);
        return $directoryIterator;
    }
}
