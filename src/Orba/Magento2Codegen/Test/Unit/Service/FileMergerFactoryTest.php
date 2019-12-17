<?php

namespace Orba\Magento2Codegen\Test\Unit\Service;

use Orba\Magento2Codegen\Service\FileMerger\MergerInterface;
use Orba\Magento2Codegen\Service\FileMergerFactory;
use PHPUnit\Framework\TestCase;

class FileMergerFactoryTest extends TestCase
{
    /**
     * @var FileMergerFactory
     */
    private $fileMErgerFactory;

    public function setUp(): void
    {
        $this->fileMErgerFactory = new FileMergerFactory();
    }

    public function testCreateReturnsNullIfMergerNotFound(): void
    {
        $result = $this->fileMErgerFactory->create('file.xml');
        $this->assertNull($result);
    }

    public function testCreateReturnsMergerIfFilenameMatchesPattern()
    {
        $mergerMock = $this->getMockBuilder(MergerInterface::class)->getMockForAbstractClass();
        $this->fileMErgerFactory->addMerger('/^.*\.xml/', $mergerMock);
        $result = $this->fileMErgerFactory->create('file.xml');
        $this->assertSame($result, $mergerMock);
    }
}