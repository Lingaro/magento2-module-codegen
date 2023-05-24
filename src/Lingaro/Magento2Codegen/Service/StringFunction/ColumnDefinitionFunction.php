<?php

/**
 * Copyright © 2023 Lingaro sp. z o.o. All rights reserved.
 * See LICENSE for license details.
 */

declare(strict_types=1);

namespace Orba\Magento2Codegen\Service\StringFunction;

use Orba\Magento2Codegen\Service\StringFunction\Helper\ColumnDefinition;
use Orba\Magento2Codegen\Service\StringFunction\Helper\DatabaseType;

use function preg_replace;
use function trim;

class ColumnDefinitionFunction implements FunctionInterface
{
    private DatabaseType $databaseTypeHelper;
    private ColumnDefinition $columnDefinitionHelper;

    public function __construct(DatabaseType $databaseTypeHelper, ColumnDefinition $columnDefinitionHelper)
    {
        $this->databaseTypeHelper = $databaseTypeHelper;
        $this->columnDefinitionHelper = $columnDefinitionHelper;
    }

    public function execute(...$args): ?string
    {
        return $this->columnDefinition(...$args);
    }

    private function columnDefinition(
        string $databaseType,
        ?string $length,
        ?bool $unsigned,
        ?bool $nullable,
        ?string $precision,
        ?string $scale
    ): string {
        $databaseType = $this->databaseTypeHelper->normalize($databaseType);
        return preg_replace('/\s+/', ' ', trim(
            $this->columnDefinitionHelper->getXsiType($databaseType) . " "
                . $this->columnDefinitionHelper->getPadding($databaseType, $length) . " "
                . $this->columnDefinitionHelper->getLength($databaseType, $length) . " "
                . $this->columnDefinitionHelper->getUnsigned($databaseType, $unsigned) . " "
                . $this->columnDefinitionHelper->getNullable($nullable) . " "
                . $this->columnDefinitionHelper->getPrecision($databaseType, $precision) . " "
                . $this->columnDefinitionHelper->getScale($databaseType, $scale)
        ));
    }
}
