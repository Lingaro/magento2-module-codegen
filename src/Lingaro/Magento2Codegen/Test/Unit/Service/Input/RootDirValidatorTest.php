<?php

/**
 * Copyright © 2023 Lingaro sp. z o.o. All rights reserved.
 * See LICENSE for license details.
 */

declare(strict_types=1);

namespace Lingaro\Magento2Codegen\Test\Unit\Service\Input;

use InvalidArgumentException;
use Lingaro\Magento2Codegen\Service\Input\RootDirValidator;
use Lingaro\Magento2Codegen\Test\Unit\TestCase;
use PHPUnit\Framework\MockObject\MockObject;
use Symfony\Component\Console\Input\InputInterface;

class RootDirValidatorTest extends TestCase
{
    private RootDirValidator $rootDirValidator;

    /**
     * @var MockObject|InputInterface
     */
    private $inputMock;

    public function setUp(): void
    {
        $this->inputMock = $this->getMockBuilder(InputInterface::class)->getMockForAbstractClass();
        $this->rootDirValidator = new RootDirValidator();
    }

    public function testValidateThrowsExceptionIfRootDirIsInApplicationBasePath(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->inputMock->expects($this->once())->method('getOption')
            ->willReturn(BP . '/dir/some/path');
        $this->rootDirValidator->validate($this->inputMock);
    }

    public function testValidateReturnsTrueIfRootDirIsOutsideApplicationBasePath(): void
    {
        $this->inputMock->expects($this->once())->method('getOption')
            ->willReturn('/some/path');
        $result = $this->rootDirValidator->validate($this->inputMock);
        $this->assertTrue($result);
    }
}
