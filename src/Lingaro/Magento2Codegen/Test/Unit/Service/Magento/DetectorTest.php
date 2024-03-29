<?php

/**
 * Copyright © 2023 Lingaro sp. z o.o. All rights reserved.
 * See LICENSE for license details.
 */

declare(strict_types=1);

namespace Lingaro\Magento2Codegen\Test\Unit\Service\Magento;

use Lingaro\Magento2Codegen\Service\FilepathUtil;
use Lingaro\Magento2Codegen\Service\Magento\Detector;
use Lingaro\Magento2Codegen\Test\Unit\TestCase;
use PHPUnit\Framework\MockObject\MockObject;
use Symfony\Component\Filesystem\Filesystem;

class DetectorTest extends TestCase
{
    private Detector $detector;

    /**
     * @var MockObject|FilepathUtil
     */
    private $filepathUtilMock;

    /**
     * @var MockObject|Filesystem
     */
    private $filesystemMock;

    public function setUp(): void
    {
        $this->filesystemMock = $this->getMockBuilder(Filesystem::class)
            ->disableOriginalConstructor()->getMock();
        $this->filepathUtilMock = $this->getMockBuilder(FilepathUtil::class)
            ->disableOriginalConstructor()->getMock();
        $this->detector = new Detector(
            $this->filesystemMock,
            $this->filepathUtilMock
        );
    }

    public function testModuleExistsInDirReturnsFalseIfModuleConfigFileDoesntExist(): void
    {
        $this->filesystemMock->expects($this->once())->method('exists')->willReturn(false);
        $result = $this->detector->moduleExistsInDir('dir');
        $this->assertFalse($result);
    }

    public function testModuleExistsInDirReturnsTrueIfModuleConfigFileExist(): void
    {
        $this->filesystemMock->expects($this->once())->method('exists')->willReturn(true);
        $result = $this->detector->moduleExistsInDir('dir');
        $this->assertTrue($result);
    }

    public function testRootEtcFileExistsInDirReturnsFalseIfEtcFileDoesntExist(): void
    {
        $this->filesystemMock->expects($this->once())->method('exists')->willReturn(false);
        $result = $this->detector->rootEtcFileExistsInDir('dir');
        $this->assertFalse($result);
    }

    public function testRootEtcFileExistsInDirReturnsTrueIfEtcFileExist(): void
    {
        $this->filesystemMock->expects($this->once())->method('exists')->willReturn(true);
        $result = $this->detector->rootEtcFileExistsInDir('dir');
        $this->assertTrue($result);
    }
}
