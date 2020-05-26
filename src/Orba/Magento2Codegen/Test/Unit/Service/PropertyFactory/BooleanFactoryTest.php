<?php

namespace Orba\Magento2Codegen\Test\Unit\Service\PropertyFactory;

use InvalidArgumentException;
use Orba\Magento2Codegen\Model\BooleanProperty;
use Orba\Magento2Codegen\Service\PropertyFactory\BooleanFactory;
use Orba\Magento2Codegen\Test\Unit\TestCase;

class BooleanFactoryTest extends TestCase
{
    /**
     * @var BooleanFactory
     */
    private $booleanFactory;

    public function setUp(): void
    {
        $this->booleanFactory = new BooleanFactory();
    }

    public function testCreateThrowsExceptionIfNameIsEmpty(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->booleanFactory->create('', []);
    }

    public function testCreateReturnsStringPropertyIfConfigIsValid(): void
    {
        $result = $this->booleanFactory->create('name', []);
        static::assertInstanceOf(BooleanProperty::class, $result);
    }
}