<?php

/**
 * @copyright Copyright © 2021 Orba. All rights reserved.
 * @author    info@orba.co
 */

declare(strict_types=1);

namespace Orba\Magento2Codegen\Test\Unit\Service\StringFunction\Helper;

use Orba\Magento2Codegen\Service\StringFunction\Helper\ColumnDefinition;
use Orba\Magento2Codegen\Service\StringFunction\Helper\DatabaseType;
use Orba\Magento2Codegen\Test\Unit\TestCase;

use function array_diff;

/**
 * @SuppressWarnings(PHPMD.TooManyPublicMethods)
 */
class ColumnDefinitionTest extends TestCase
{
    private ColumnDefinition $columnDefinition;

    public function setUp(): void
    {
        $this->columnDefinition = new ColumnDefinition();
    }

    /**
     * @dataProvider getNotIntTypes()
     */
    public function testGetPaddingReturnsEmptyStringForNotIntType(string $type): void
    {
        $result = $this->columnDefinition->getPadding($type, '10');
        $this->assertSame('', $result);
    }

    /**
     * @dataProvider getIntTypes()
     */
    public function testGetPaddingReturnsFormattedStringWithDefaultValueForIntTypeIfLengthIsNull(string $type): void
    {
        $result = $this->columnDefinition->getPadding($type, null);
        $this->assertMatchesRegularExpression('/padding="\d+"/', $result);
    }

    /**
     * @dataProvider getIntTypes()
     */
    public function testGetPaddingReturnsFormattedStringWithSpecifiedLengthForIntType(string $type): void
    {
        $result = $this->columnDefinition->getPadding($type, '13');
        $this->assertSame('padding="13"', $result);
    }

    /**
     * @dataProvider getNotStringTypes()
     */
    public function testGetLengthReturnsEmptyStringForNotStringType(string $type): void
    {
        $result = $this->columnDefinition->getLength($type, '10');
        $this->assertSame('', $result);
    }

    /**
     * @dataProvider getStringTypes()
     */
    public function testGetLengthReturnsFormattedStringWithDefaultValueForStringTypeIfLengthIsNull(string $type): void
    {
        $result = $this->columnDefinition->getLength($type, null);
        $this->assertMatchesRegularExpression('/length="\d+"/', $result);
    }

    /**
     * @dataProvider getStringTypes()
     */
    public function testGetLengthReturnsFormattedStringWithSpecifiedLengthForStringType(string $type): void
    {
        $result = $this->columnDefinition->getLength($type, '13');
        $this->assertSame('length="13"', $result);
    }

    /**
     * @dataProvider getNotNumericTypes()
     */
    public function testGetUnsignedReturnsEmptyStringForNotNumericType(string $type): void
    {
        $result = $this->columnDefinition->getUnsigned($type, true);
        $this->assertSame('', $result);
    }

    /**
     * @dataProvider getNumericTypes()
     */
    public function testGetUnsignedReturnsFormattedStringWithDefaultValForNumericTypeIfUnsignedNull(string $type): void
    {
        $result = $this->columnDefinition->getUnsigned($type, null);
        $this->assertMatchesRegularExpression('/unsigned="(true|false)"/', $result);
    }

    /**
     * @dataProvider getNumericTypes()
     */
    public function testGetUnsignedReturnsFormattedStringWithSpecifiedUnsignedForNumericType(string $type): void
    {
        $resultTrue = $this->columnDefinition->getUnsigned($type, true);
        $resultFalse = $this->columnDefinition->getUnsigned($type, false);
        $this->assertSame('unsigned="true"', $resultTrue);
        $this->assertSame('unsigned="false"', $resultFalse);
    }

    public function testGetNullableReturnsFormattedStringWithFalseValueIfNullableIsNull(): void
    {
        $result = $this->columnDefinition->getNullable(null);
        $this->assertMatchesRegularExpression('/nullable="false"/', $result);
    }

    public function testGetNullableReturnsFormattedStringWithSpecifiedValue(): void
    {
        $resultTrue = $this->columnDefinition->getNullable(true);
        $resultFalse = $this->columnDefinition->getNullable(false);
        $this->assertMatchesRegularExpression('/nullable="true"/', $resultTrue);
        $this->assertMatchesRegularExpression('/nullable="false"/', $resultFalse);
    }

    /**
     * @dataProvider getNotDecimalTypes()
     */
    public function testGetPrecisionReturnsEmptyStringForNotDecimalType(string $type): void
    {
        $result = $this->columnDefinition->getPrecision($type, '10');
        $this->assertSame('', $result);
    }

    public function testGetPrecisionReturnsEmptyStringIfPrecisionIsNull(): void
    {
        $result = $this->columnDefinition->getPrecision($this->getDecimalTypes()[0][0], null);
        $this->assertSame('', $result);
    }

    /**
     * @dataProvider getDecimalTypes()
     */
    public function testGetPrecisionReturnsFormattedStringWithSpecifiedPrecisionForDecimalType(string $type): void
    {
        $result = $this->columnDefinition->getPrecision($type, '14');
        $this->assertSame('precision="14"', $result);
    }

    /**
     * @dataProvider getNotDecimalTypes()
     */
    public function testGetScaleReturnsEmptyStringForNotDecimalType(string $type): void
    {
        $result = $this->columnDefinition->getScale($type, '3');
        $this->assertSame('', $result);
    }

    public function testGetScaleReturnsEmptyStringIfScaleIsNull(): void
    {
        $result = $this->columnDefinition->getScale($this->getDecimalTypes()[0][0], null);
        $this->assertSame('', $result);
    }

    /**
     * @dataProvider getDecimalTypes()
     */
    public function testGetScaleReturnsFormattedStringWithSpecifiedScaleForDecimalType(string $type): void
    {
        $result = $this->columnDefinition->getScale($type, '3');
        $this->assertSame('scale="3"', $result);
    }

    public function getAllTypes(): array
    {
        $types = [];
        foreach (DatabaseType::ALLOWED_TYPES as $type) {
            $types[] = [$type];
        };
        return $types;
    }

    public function getIntTypes(): array
    {
        $types = [];
        foreach (DatabaseType::INT_TYPES as $type) {
            $types[] = [$type];
        };
        return $types;
    }

    public function getStringTypes(): array
    {
        $types = [];
        foreach (DatabaseType::STRING_TYPES as $type) {
            $types[] = [$type];
        };
        return $types;
    }

    public function getDecimalTypes(): array
    {
        $types = [];
        foreach (DatabaseType::DECIMAL_TYPES as $type) {
            $types[] = [$type];
        };
        return $types;
    }

    public function getNumericTypes(): array
    {
        $types = [];
        foreach (array_merge(DatabaseType::INT_TYPES, DatabaseType::DECIMAL_TYPES) as $type) {
            $types[] = [$type];
        };
        return $types;
    }

    public function getNotIntTypes(): array
    {
        $types = [];
        foreach (array_diff(DatabaseType::ALLOWED_TYPES, DatabaseType::INT_TYPES) as $type) {
            $types[] = [$type];
        };
        return $types;
    }

    public function getNotStringTypes(): array
    {
        $types = [];
        foreach (array_diff(DatabaseType::ALLOWED_TYPES, DatabaseType::STRING_TYPES) as $type) {
            $types[] = [$type];
        };
        return $types;
    }

    public function getNotDecimalTypes(): array
    {
        $types = [];
        foreach (array_diff(DatabaseType::ALLOWED_TYPES, DatabaseType::DECIMAL_TYPES) as $type) {
            $types[] = [$type];
        };
        return $types;
    }

    public function getNotNumericTypes(): array
    {
        $types = [];
        foreach (
            array_diff(
                DatabaseType::ALLOWED_TYPES,
                DatabaseType::INT_TYPES,
                DatabaseType::DECIMAL_TYPES
            ) as $type
        ) {
            $types[] = [$type];
        };
        return $types;
    }
}
