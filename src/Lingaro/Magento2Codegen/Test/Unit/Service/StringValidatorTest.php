<?php

/**
 * Copyright © 2023 Lingaro sp. z o.o. All rights reserved.
 * See LICENSE for license details.
 */

namespace Lingaro\Magento2Codegen\Test\Unit\Service;

use Lingaro\Magento2Codegen\Service\StringValidator;
use Lingaro\Magento2Codegen\Service\StringValidator\ValidatorInterface;
use Lingaro\Magento2Codegen\Test\Unit\TestCase;
use InvalidArgumentException;

class StringValidatorTest extends TestCase
{
    public function testValidateExceptionMissingValidationRule(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $validator = $this->createMock(ValidatorInterface::class);
        $result = new StringValidator(['bar' => $validator]);
        $result->validate('value', ['foo' => true]);
    }

    public function testValidateExceptionWrongValidationRuleInstance()
    {
        $this->expectException(InvalidArgumentException::class);
        $validator = $this->createMock(ValidatorInterface::class);
        $result = new StringValidator(['bar' => $validator, 'foo' => (object) 'empty object']);
        $result->validate('value', ['bar' => true, 'foo' => true]);
    }
}
