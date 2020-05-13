<?php

namespace Orba\Magento2Codegen\Service\StringFunction;

use Orba\Magento2Codegen\Service\StringFilter\SnakeCaseFilter;
use Orba\Magento2Codegen\Service\StringFunction\Helper\DatabaseType;

class FullTextIndexFunction implements FunctionInterface
{
    const FULLTEXT_TYPES = ['varchar'];

    /**
     * @var SnakeCaseFilter
     */
    private $snakeCaseFilter;

    /**
     * @var DatabaseType
     */
    private $databaseTypeHelper;

    public function __construct(SnakeCaseFilter $snakeCaseFilter, DatabaseType $databaseTypeHelper)
    {
        $this->snakeCaseFilter = $snakeCaseFilter;
        $this->databaseTypeHelper = $databaseTypeHelper;
    }

    public function execute(...$args): ?string
    {
        return $this->fullTextIndex(...$args);
    }

    /**
     * @param string $vendorName
     * @param string $moduleName
     * @param string $entityName
     * @param array $fields
     * @return string
     * @throws \InvalidArgumentException
     */
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
