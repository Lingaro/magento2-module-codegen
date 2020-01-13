<?php

namespace Orba\Magento2Codegen\Test\Unit\Service\PropertyFactory;

use InvalidArgumentException;
use Orba\Magento2Codegen\Model\StringProperty;
use Orba\Magento2Codegen\Service\PropertyFactory\StringFactory;
use Orba\Magento2Codegen\Test\Unit\TestCase;

class StringFactoryTest extends TestCase
{
    /**
     * @var StringFactory
     */
    private $stringFactory;

    public function setUp(): void
    {
        $this->stringFactory = new StringFactory();
    }

    public function testCreateThrowsExceptionIfNameIsEmpty(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->stringFactory->create('', []);
    }

    public function testCreateReturnsStringPropertyIfConfigIsValid(): void
    {
        $result = $this->stringFactory->create('name', []);
        $this->assertInstanceOf(StringProperty::class, $result);
    }
}