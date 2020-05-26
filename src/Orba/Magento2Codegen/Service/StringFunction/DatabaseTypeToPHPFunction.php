<?php

namespace Orba\Magento2Codegen\Service\StringFunction;

use Orba\Magento2Codegen\Service\StringFunction\Helper\DatabaseType;

class DatabaseTypeToPHPFunction implements FunctionInterface
{
    /**
     * @var DatabaseType
     */
    private $databaseTypeHelper;

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