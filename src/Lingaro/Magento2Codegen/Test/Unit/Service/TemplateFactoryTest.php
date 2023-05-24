<?php

/**
 * Copyright © 2023 Lingaro sp. z o.o. All rights reserved.
 * See LICENSE for license details.
 */

declare(strict_types=1);

namespace Orba\Magento2Codegen\Test\Unit\Service;

use InvalidArgumentException;
use Orba\Magento2Codegen\Model\Template;
use Orba\Magento2Codegen\Service\TemplateBuilder;
use Orba\Magento2Codegen\Service\TemplateFactory;
use Orba\Magento2Codegen\Service\TemplateFile;
use Orba\Magento2Codegen\Test\Unit\TestCase;
use PHPUnit\Framework\MockObject\MockObject;

class TemplateFactoryTest extends TestCase
{
    private TemplateFactory $templateFactory;

    /**
     * @var MockObject|TemplateFile
     */
    private $templateFileMock;

    /**
     * @var MockObject|TemplateBuilder
     */
    private $templateBuilderMock;

    public function setUp(): void
    {
        $this->templateFileMock = $this->getMockBuilder(TemplateFile::class)
            ->disableOriginalConstructor()->getMock();
        $this->templateBuilderMock = $this->getMockBuilder(TemplateBuilder::class)
            ->disableOriginalConstructor()->getMock();
        $this->templateFactory = new TemplateFactory($this->templateFileMock, $this->templateBuilderMock);
    }

    public function testCreateThrowsExceptionIfTemplateDoesntExist(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->templateFileMock->expects($this->once())->method('exists')->willReturn(false);
        $this->templateFactory->create('foo');
    }

    public function testCreateReturnsTemplateModelIfTemplateExists(): void
    {
        $this->templateFileMock->expects($this->once())->method('exists')->willReturn(true);
        $result = $this->templateFactory->create('bar');
        $this->assertInstanceOf(Template::class, $result);
    }
}
