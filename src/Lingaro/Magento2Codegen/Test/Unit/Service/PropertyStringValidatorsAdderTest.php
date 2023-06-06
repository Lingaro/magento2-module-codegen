<?php

/**
 * Copyright © 2023 Lingaro sp. z o.o. All rights reserved.
 * See LICENSE for license details.
 */

declare(strict_types=1);

namespace Lingaro\Magento2Codegen\Test\Unit\Service;

use InvalidArgumentException;
use Lingaro\Magento2Codegen\Model\StringProperty;
use Lingaro\Magento2Codegen\Service\PropertyStringValidatorsAdder;
use Lingaro\Magento2Codegen\Service\StringValidator;
use Lingaro\Magento2Codegen\Test\Unit\TestCase;
use PHPUnit\Framework\MockObject\MockObject;

class PropertyStringValidatorsAdderTest extends TestCase
{
    private PropertyStringValidatorsAdder $validatorsAdder;
    private MockObject $stringValidatorMock;

    public function setUp(): void
    {
        $this->stringValidatorMock = $this->createPartialMock(
            StringValidator::class,
            ['getAllowedValidators']
        );
        $this->validatorsAdder = new PropertyStringValidatorsAdder($this->stringValidatorMock);
    }

    public function testAddValidatorsExceptionValidatorsNotArray(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $property = new StringProperty();
        $this->validatorsAdder->execute($property, ['validators' => 'not_array']);
    }

    public function testAddValidatorsExceptionValidatorsArrayHasNonStringKey(): void
    {
        $this->expectException(InvalidArgumentException::class);

        $this->stringValidatorMock->expects($this->once())
            ->method('getAllowedValidators')->willReturn([]);

        $property = new StringProperty();
        $this->validatorsAdder->execute($property, ['validators' => [0 => true]]);
    }

    public function testAddValidatorsExceptionNotAllowedValidatorUsed(): void
    {
        $this->expectException(InvalidArgumentException::class);

        $this->stringValidatorMock->expects($this->once())
            ->method('getAllowedValidators')->willReturn(['bar']);

        $property = new StringProperty();
        $this->validatorsAdder->execute($property, ['validators' => ['foo' => true]]);
    }

    public function testAddValidatorsSuccessForNoValidators(): void
    {
        $property = new StringProperty();
        $this->validatorsAdder->execute($property, []);
        $this->assertEmpty($property->getValidators());
    }

    public function testAddValidatorSuccess(): void
    {
        $this->stringValidatorMock->expects($this->once())
            ->method('getAllowedValidators')->willReturn(['foo']);

        $property = new StringProperty();
        $this->validatorsAdder->execute($property, ['validators' => ['foo' => true]]);
        $this->assertSame(['foo' => true], $property->getValidators());
    }
}
