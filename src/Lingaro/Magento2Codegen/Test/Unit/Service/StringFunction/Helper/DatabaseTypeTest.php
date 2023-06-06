<?php

/**
 * Copyright © 2023 Lingaro sp. z o.o. All rights reserved.
 * See LICENSE for license details.
 */

declare(strict_types=1);

namespace Lingaro\Magento2Codegen\Test\Unit\Service\StringFunction\Helper;

use Exception;
use InvalidArgumentException;
use Lingaro\Magento2Codegen\Service\StringFunction\Helper\DatabaseType;
use Lingaro\Magento2Codegen\Test\Unit\TestCase;

class DatabaseTypeTest extends TestCase
{
    private DatabaseType $databaseType;

    public function setUp(): void
    {
        $this->databaseType = new DatabaseType();
    }

    public function testNormalizeReturnsLowercasedString(): void
    {
        $result = $this->databaseType->normalize('SoMeStRiNg');
        $this->assertSame('somestring', $result);
    }

    public function testValidateThrowsExceptionForNotAllowedType(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->databaseType->validate('invalid');
    }

    public function testValidateDoesNotThrowExceptionForAllowedTypes(): void
    {
        $thrownExceptions = 0;
        foreach (DatabaseType::ALLOWED_TYPES as $type) {
            try {
                $this->databaseType->validate($type);
            } catch (Exception $e) {
                $thrownExceptions++;
            }
        }
        $this->assertSame(0, $thrownExceptions);
    }
}
