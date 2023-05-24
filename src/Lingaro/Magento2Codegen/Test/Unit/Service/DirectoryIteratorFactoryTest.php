<?php

/**
 * Copyright © 2023 Lingaro sp. z o.o. All rights reserved.
 * See LICENSE for license details.
 */

declare(strict_types=1);

namespace Lingaro\Magento2Codegen\Test\Unit\Service;

use Lingaro\Magento2Codegen\Service\DirectoryIteratorFactory;
use Lingaro\Magento2Codegen\Test\Unit\TestCase;
use UnexpectedValueException;
use RecursiveDirectoryIterator;

class DirectoryIteratorFactoryTest extends TestCase
{
    private DirectoryIteratorFactory $directoryIteratorFactory;

    public function setUp(): void
    {
        $this->directoryIteratorFactory = new DirectoryIteratorFactory();
    }

    public function testIteratorThrowExceptionForNonExistingDirectory(): void
    {
        $path = $this->getPath(BP, 'non/existing/path');
        $this->expectException(UnexpectedValueException::class);
        $this->expectExceptionMessage("Directory not found: " . $path);
        $this->directoryIteratorFactory->create($path);
    }

    public function testIteratorNotThrowExceptionForExistingDirectory(): void
    {
        $path = $this->getPath(BP, 'extra/templates/source1');
        $iterator = $this->directoryIteratorFactory->create($path);
        $this->assertInstanceOf(RecursiveDirectoryIterator::class, $iterator);
    }

    private function getPath(string $root, string $path): string
    {
        $root = rtrim($root, '/');
        $path = ltrim($path, '/');
        return $root . '/' . $path;
    }
}
