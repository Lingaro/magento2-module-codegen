<?php

namespace Orba\Magento2Codegen\Service\Twig;

use ErrorException;
use Orba\Magento2Codegen\Service\StringFilter\SnakeCaseFilter;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

/**
 * Class FunctionsExtension
 * @package Orba\Magento2Codegen\Service\Twig
 */
class FunctionsExtension extends AbstractExtension
{
    private const INT_TYPES = ['int', 'smallint', 'bigint', 'tinyint', 'timestamp'];
    private const DECIMAL_TYPES = ['decimal', 'float', 'double'];
    private const BOOLEAN_TYPES = ['boolean'];
    private const TEXT_TYPES = ['text', 'varchar'];
    private const ALLOWED_TYPES = ['bigint', 'blob', 'boolean', 'date', 'datetime',
'decimal', 'double', 'float', 'int', 'smallint', 'text', 'timestamp', 'tinyint',
'varbinary', 'varchar'];

    /** @var SnakeCaseFilter */
    private $snakeCaseFilter;

    public function __construct(SnakeCaseFilter $snakeCaseFilter)
    {
        $this->snakeCaseFilter = $snakeCaseFilter;
    }

    /**
     * @return array|TwigFunction[]
     */
    public function getFunctions(): array
    {
        return [
            new TwigFunction('columnDefinition', [$this, 'columnDefinition']),
            new TwigFunction('databaseTypeToPHP', [$this, 'databaseTypeToPHP']),
            new TwigFunction('fullTextIndex', [$this, 'fullTextIndex'])
        ];
    }

    /**
     * @param string $databaseType
     * @param string|null $length
     * @param string|null $unsigned
     * @param string|null $nullable
     * @param string|null $precision
     * @param string|null $scale
     * @return string
     * @throws ErrorException
     */
    public function columnDefinition(string $databaseType, ?string $length, ?string $unsigned, ?string $nullable, ?string $precision, ?string $scale): string
    {
        $databaseType = $this->normalizeDatabaseType($databaseType);
        $def=  preg_replace('/\s+/', ' ', trim(
            $this->getXsiType($databaseType) . " "
            . $this->getPadding($databaseType, $length) . " "
            . $this->getLength($databaseType, $length) . " "
            . $this->getUnsigned($databaseType, $unsigned) . " "
            . $this->getNullable($nullable) . " "
            . $this->getPrecision($databaseType, $precision) . " "
            . $this->getScale($databaseType, $scale)
            )
        );
        return $def;
    }

    /**
     * @param string $databaseType
     * @return string
     * @throws ErrorException
     */
    public function databaseTypeToPHP(string $databaseType): string
    {
        $databaseType = $this->normalizeDatabaseType($databaseType);
        switch($databaseType) {
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

    /**
     * @param string $vendorName
     * @param string $moduleName
     * @param string $entityName
     * @param array $fields
     * @return string|void
     * @throws ErrorException
     */
    public function fullTextIndex(string $vendorName, string $moduleName, string $entityName, array $fields)
    {
        $cols = [];
        foreach ($fields as $field) {
            $dbType = $this->normalizeDatabaseType($field['databaseType']);
            if (in_array($dbType, self::TEXT_TYPES)) {
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
    }

    /**
     * @param string $databaseType
     * @return string
     */
    private function getXsiType(string $databaseType): string
    {
        return $this->format('xsi:type', $databaseType);
    }

    /**
     * @param string $databaseType
     * @return string
     * @throws ErrorException
     */
    private function normalizeDatabaseType(string $databaseType): string
    {
        $databaseType = strtolower($databaseType);
        if (!in_array($databaseType, self::ALLOWED_TYPES))
        {
            throw new ErrorException(' Wrong database type: ' . $databaseType
                . ' Allowed types: ' . implode(",", self::ALLOWED_TYPES));
        }
        return $databaseType;
    }

    /**
     * @param string $databaseType
     * @param string|null $length
     * @return string
     */
    private function getPadding(string $databaseType, ?string $length): string
    {
        if (!in_array($databaseType, self::INT_TYPES)) {
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

    /**
     * @param string $databaseType
     * @param string|null $length
     * @return string
     */
    private function getLength(string $databaseType, ?string $length): string
    {
        if (in_array($databaseType, array_merge(self::INT_TYPES, self::DECIMAL_TYPES, self::BOOLEAN_TYPES))) {
            return '';
        }
        if (empty($length)) {
            $length = 255;
        }
        return $this->format('length', $length);
    }

    /**
     * @param string $databaseType
     * @param string|null $unsigned
     * @return string
     */
    private function getUnsigned(string $databaseType, ?string $unsigned): string
    {
        if (!in_array($databaseType, array_merge(self::INT_TYPES, self::DECIMAL_TYPES))) {
            return '';
        }
        return $this->format('unsigned', $this->getStringBoolean($unsigned));
    }

    /**
     * @param string|null $nullable
     * @return string
     */
    private function getNullable(?string $nullable): string
    {
        return $this->format('nullable', $this->getStringBoolean($nullable));
    }

    /**
     * @param string $databaseType
     * @param string|null $precision
     * @return string
     */
    private function getPrecision(string $databaseType, ?string $precision): string
    {
        if (!in_array($databaseType, self::DECIMAL_TYPES) || empty($precision)) {
            return '';
        }
        return $this->format('precision', $precision);
    }

    /**
     * @param string $databaseType
     * @param string|null $scale
     * @return string
     */
    private function getScale(string $databaseType, ?string $scale): string
    {
        if (!in_array($databaseType, self::DECIMAL_TYPES) || empty($scale)) {
            return '';
        }
        return $this->format('scale', $scale);
    }

    /**
     * @param string|null $in
     * @return string
     */
    private function getStringBoolean(?string $in): string
    {
        if ((strtolower($in)) == 'false') {
            return 'false';
        }
        if ($in) {
            return 'true';
        } else {
            return 'false';
        }
    }

    /**
     * @param $token
     * @param $value
     * @return string
     */
    private function format($token, $value): string
    {
        return $token . '="' . $value . '"';
    }
}
