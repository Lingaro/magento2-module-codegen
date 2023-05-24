<?php

/**
 * Copyright © 2023 Lingaro sp. z o.o. All rights reserved.
 * See LICENSE for license details.
 */

declare(strict_types=1);

namespace Lingaro\Magento2Codegen\Service\StringFunction;

use Lingaro\Magento2Codegen\Service\StringFilter\SnakeCaseFilter;
use Lingaro\Magento2Codegen\Service\StringFunction\Helper\DatabaseType;

use function implode;
use function in_array;

class FullTextIndexFunction implements FunctionInterface
{
    public const FULLTEXT_TYPES = ['varchar'];

    private SnakeCaseFilter $snakeCaseFilter;
    private DatabaseType $databaseTypeHelper;

    public function __construct(SnakeCaseFilter $snakeCaseFilter, DatabaseType $databaseTypeHelper)
    {
        $this->snakeCaseFilter = $snakeCaseFilter;
        $this->databaseTypeHelper = $databaseTypeHelper;
    }

    public function execute(...$args): ?string
    {
        return $this->fullTextIndex(...$args);
    }

    private function fullTextIndex(string $vendorName, string $moduleName, string $entityName, array $fields): string
    {
        $cols = [];
        foreach ($fields as $field) {
            $dbType = $this->databaseTypeHelper->normalize($field['databaseType']);
            if (in_array($dbType, self::FULLTEXT_TYPES)) {
                $cols[] = '<column name="' . $this->snakeCaseFilter->filter($field['name']) . '"/>';
            }
        }
        if ($cols) {
            return '<index referenceId="FTI_'
                . $this->snakeCaseFilter->filter($vendorName) . '_'
                . $this->snakeCaseFilter->filter($moduleName) . '_'
                . $this->snakeCaseFilter->filter($entityName) . '" '
                . 'indexType="fulltext">' . "\n"
                . implode("\n", $cols) . "\n"
                . '</index>';
        }
        return '';
    }
}
