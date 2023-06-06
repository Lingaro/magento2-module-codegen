<?php

/**
 * Copyright © 2023 Lingaro sp. z o.o. All rights reserved.
 * See LICENSE for license details.
 */

declare(strict_types=1);

namespace Lingaro\Magento2Codegen\Test\Unit\Service;

use Lingaro\Magento2Codegen\Service\FileMerger\MergerInterface;
use Lingaro\Magento2Codegen\Service\FileMergerFactory;
use Lingaro\Magento2Codegen\Test\Unit\TestCase;
use PHPUnit\Framework\MockObject\MockObject;

class FileMergerFactoryTest extends TestCase
{
    private FileMergerFactory $fileMergerFactory;

    public function setUp(): void
    {
        $this->fileMergerFactory = new FileMergerFactory();
    }

    public function testCreateReturnsNullIfMergerNotFound(): void
    {
        $result = $this->fileMergerFactory->create('file.xml');
        $this->assertNull($result);
    }

    public function testCreateReturnsMergerIfFilenameMatchesPattern(): void
    {
        /** @var MockObject|MergerInterface $mergerMock */
        $mergerMock = $this->getMockBuilder(MergerInterface::class)->getMockForAbstractClass();
        $this->fileMergerFactory->addMerger('/^.*\.xml/', $mergerMock);
        $result = $this->fileMergerFactory->create('file.xml');
        $this->assertSame($result, $mergerMock);
    }
}
