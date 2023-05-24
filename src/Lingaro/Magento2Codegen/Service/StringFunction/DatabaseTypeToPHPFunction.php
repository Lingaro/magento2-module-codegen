<?php

/**
 * Copyright © 2023 Lingaro sp. z o.o. All rights reserved.
 * See LICENSE for license details.
 */

declare(strict_types=1);

namespace Lingaro\Magento2Codegen\Service\StringFunction;

use Lingaro\Magento2Codegen\Service\StringFunction\Helper\DatabaseType;

class DatabaseTypeToPHPFunction implements FunctionInterface
{
    private DatabaseType $databaseTypeHelper;

    public function __construct(DatabaseType $databaseTypeHelper)
    {
        $this->databaseTypeHelper = $databaseTypeHelper;
    }

    public function execute(...$args): ?string
    {
        return $this->databaseTypeToPHP(...$args);
    }

    private function databaseTypeToPHP(string $databaseType): string
    {
        $this->databaseTypeHelper->validate($databaseType);
        $databaseType = $this->databaseTypeHelper->normalize($databaseType);
        switch ($databaseType) {
            case 'int':
            case 'smallint':
                return 'int';
            case 'boolean':
                return 'bool';
            case 'decimal':
            case 'float':
            case 'real':
                return 'float';
            default:
                return 'string';
        }
    }
}
