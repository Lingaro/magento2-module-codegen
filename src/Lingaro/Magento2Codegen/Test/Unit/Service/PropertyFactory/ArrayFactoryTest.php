<?php

/**
 * Copyright © 2023 Lingaro sp. z o.o. All rights reserved.
 * See LICENSE for license details.
 */

declare(strict_types=1);

namespace Lingaro\Magento2Codegen\Test\Unit\Service\PropertyFactory;

use Lingaro\Magento2Codegen\Model\ArrayProperty;
use Lingaro\Magento2Codegen\Service\PropertyBuilder;
use Lingaro\Magento2Codegen\Service\PropertyFactory;
use Lingaro\Magento2Codegen\Service\PropertyFactory\ArrayFactory;
use Lingaro\Magento2Codegen\Test\Unit\TestCase;
use Lingaro\Magento2Codegen\Service\StringValidator;
use PHPUnit\Framework\MockObject\MockObject;
use RuntimeException;

class ArrayFactoryTest extends TestCase
{
    private ArrayFactory $arrayFactory;

    /**
     * @var MockObject|PropertyFactory
     */
    private $propertyFactoryMock;

    public function setUp(): void
    {
        $this->propertyFactoryMock = $this->getMockBuilder(PropertyFactory::class)
            ->disableOriginalConstructor()->getMock();
        $this->arrayFactory = new ArrayFactory(new PropertyBuilder());
    }

    public function testCreateThrowsExceptionIfPropertyFactoryIsUnset(): void
    {
        $this->expectException(RuntimeException::class);
        $this->arrayFactory->create('name', []);
    }

    public function testCreateReturnsArrayProperty(): void
    {
        $this->arrayFactory->setPropertyFactory($this->propertyFactoryMock);
        $result = $this->arrayFactory->create('name', ['children' => ['foo' => []]]);
        $this->assertInstanceOf(ArrayProperty::class, $result);
    }
}
