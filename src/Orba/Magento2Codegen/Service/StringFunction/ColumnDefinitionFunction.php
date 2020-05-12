<?php

namespace Orba\Magento2Codegen\Service\StringFunction;

use Orba\Magento2Codegen\Service\StringFunction\Helper\ColumnDefinition;
use Orba\Magento2Codegen\Service\StringFunction\Helper\DatabaseType;

class ColumnDefinitionFunction implements FunctionInterface
{
    /**
     * @var DatabaseType
     */
    private $databaseTypeHelper;

    /**
     * @var ColumnDefinition
     */
    private $columnDefinitionHelper;

    public function __construct(DatabaseType $databaseTypeHelper, ColumnDefinition $columnDefinitionHelper)
    {
        $this->databaseTypeHelper = $databaseTypeHelper;
        $this->columnDefinitionHelper = $columnDefinitionHelper;
    }

    public function execute(...$args): ?string
    {
        return $this->columnDefinition(...$args);
    }

    private function columnDefinition(string $databaseType, ?string $length, ?string $unsigned, ?string $nullable, ?string $precision, ?string $scale): string
    {
        $databaseType = $this->databaseTypeHelper->normalize($databaseType);
        $def = preg_replace('/\s+/', ' ', trim(
                $this->columnDefinitionHelper->getXsiType($databaseType) . " "
                . $this->columnDefinitionHelper->getPadding($databaseType, $length) . " "
                . $this->columnDefinitionHelper->getLength($databaseType, $length) . " "
                . $this->columnDefinitionHelper->getUnsigned($databaseType, $unsigned) . " "
                . $this->columnDefinitionHelper->getNullable($nullable) . " "
                . $this->columnDefinitionHelper->getPrecision($databaseType, $precision) . " "
                . $this->columnDefinitionHelper->getScale($databaseType, $scale)
            )
        );
        return $def;
    }
}
