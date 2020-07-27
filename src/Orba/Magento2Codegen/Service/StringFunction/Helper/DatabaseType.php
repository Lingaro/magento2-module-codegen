<?php

namespace Orba\Magento2Codegen\Service\StringFunction\Helper;

use InvalidArgumentException;

class DatabaseType
{
    const INT_TYPES = ['int', 'smallint', 'bigint', 'tinyint', 'timestamp'];
    const DECIMAL_TYPES = ['decimal', 'float', 'double'];
    const BOOLEAN_TYPES = ['boolean'];
    const STRING_TYPES = ['varbinary', 'varchar'];
    const ALLOWED_TYPES = ['bigint', 'blob', 'boolean', 'date', 'datetime',
        'decimal', 'double', 'float', 'int', 'smallint', 'text', 'timestamp', 'tinyint',
        'varbinary', 'varchar'];

    public function normalize(string $databaseType): string
    {
        return strtolower($databaseType);
    }

    public function validate(string $databaseType): void
    {
        if (!in_array($databaseType, self::ALLOWED_TYPES)) {
            throw new InvalidArgumentException('Wrong database type: ' . $databaseType
                . ' Allowed types: ' . implode(",", self::ALLOWED_TYPES));
        }
    }
}
