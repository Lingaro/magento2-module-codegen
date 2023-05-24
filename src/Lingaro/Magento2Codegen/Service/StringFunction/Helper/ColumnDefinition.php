<?php

/**
 * Copyright © 2023 Lingaro sp. z o.o. All rights reserved.
 * See LICENSE for license details.
 */

declare(strict_types=1);

namespace Orba\Magento2Codegen\Service\StringFunction\Helper;

use function array_merge;
use function in_array;

class ColumnDefinition
{
    public function getXsiType(string $databaseType): string
    {
        return $this->format('xsi:type', $databaseType);
    }

    public function getPadding(string $databaseType, ?string $length): string
    {
        if (!in_array($databaseType, DatabaseType::INT_TYPES)) {
            return '';
        }
        if (empty($length)) {
            switch ($databaseType) {
                case 'int':
                case 'timestamp':
                    $length = 10;
                    break;
                case 'smallint':
                    $length = 5;
                    break;
                default:
                    $length = 10;
            }
        }
        return $this->format('padding', $length);
    }

    public function getLength(string $databaseType, ?string $length): string
    {
        if (!in_array($databaseType, DatabaseType::STRING_TYPES)) {
            return '';
        }
        if (empty($length)) {
            $length = 255;
        }
        return $this->format('length', $length);
    }

    public function getUnsigned(string $databaseType, ?bool $unsigned): string
    {
        if (!in_array($databaseType, array_merge(DatabaseType::INT_TYPES, DatabaseType::DECIMAL_TYPES))) {
            return '';
        }
        return $this->format('unsigned', $this->getStringBoolean((bool) $unsigned));
    }

    public function getNullable(?bool $nullable): string
    {
        return $this->format('nullable', $this->getStringBoolean((bool) $nullable));
    }

    public function getPrecision(string $databaseType, ?string $precision): string
    {
        if (!in_array($databaseType, DatabaseType::DECIMAL_TYPES) || empty($precision)) {
            return '';
        }
        return $this->format('precision', $precision);
    }

    public function getScale(string $databaseType, ?string $scale): string
    {
        if (!in_array($databaseType, DatabaseType::DECIMAL_TYPES) || empty($scale)) {
            return '';
        }
        return $this->format('scale', $scale);
    }

    private function getStringBoolean(bool $in): string
    {
        return $in ? 'true' : 'false';
    }

    /**
     * @param mixed $value
     */
    private function format(string $token, $value): string
    {
        return $token . '="' . $value . '"';
    }
}
