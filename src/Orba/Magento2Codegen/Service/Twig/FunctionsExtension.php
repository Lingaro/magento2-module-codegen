<?php

namespace Orba\Magento2Codegen\Service\Twig;

use ErrorException;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

/**
 * Class FunctionsExtension
 * @package Orba\Magento2Codegen\Service\Twig
 */
class FunctionsExtension extends AbstractExtension
{
    private const INT_TYPES = ['int', 'smallint', 'timestamp'];
    private const DECIMAL_TYPES = ['decimal', 'float', 'real'];
    private const ALLOWED_TYPES = ['blob', 'boolean', 'date', 'datetime',
'decimal', 'float', 'int', 'real', 'smallint', 'text', 'timestamp',
'varbinary', 'varchar'];

    /**
     * @return array|TwigFunction[]
     */
    public function getFunctions(): array
    {
        return [
            new TwigFunction('columnDefinition', [$this, 'columnDefinition']),
            new TwigFunction('databaseTypeToPHP', [$this, 'databaseTypeToPHP']),
        ];
    }

    /**
     * @param string $databaseType
     * @param string $length |null
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
        return trim(
            $this->getXsiType($databaseType)
            . $this->getPadding($databaseType, $length)
            . $this->getLength($databaseType, $length)
            . $this->getUnsigned($databaseType, $unsigned)
            . $this->getNullable($nullable)
            . $this->getPrecision($databaseType, $precision)
            . $this->getScale($databaseType, $scale)
        );
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
     * @param string $length
     * @return string
     */
    private function getPadding(string $databaseType, string $length)
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
            }
        }
        return $this->format('padding', $length);
    }

    /**
     * @param string $databaseType
     * @param string $length
     * @return string
     */
    private function getLength(string $databaseType, string $length)
    {
        if (in_array($databaseType, self::INT_TYPES)) {
            return '';
        }
        if (empty($length)) {
            $length = 255;
        }
        return $this->format('length', $length);
    }

    /**
     * @param string $databaseType
     * @param string $unsigned
     * @return string
     */
    private function getUnsigned(string $databaseType, string $unsigned)
    {
        if (!in_array($databaseType, array_merge(self::INT_TYPES, self::DECIMAL_TYPES))) {
            return '';
        }
        return $this->format('unsigned', $this->getStringBoolean($unsigned));
    }

    /**
     * @param string $nullable
     * @return string
     */
    private function getNullable(string $nullable): string
    {
        return $this->format('nullable', $this->getStringBoolean($nullable));
    }

    /**
     * @param string $databaseType
     * @param string $precision
     * @return string
     */
    private function getPrecision(string $databaseType, string $precision): string
    {
        if (!in_array($databaseType, self::DECIMAL_TYPES)) {
            return '';
        }
        if (!$precision) {
            //as mysql default
            $precision = '10';
        }
        return $this->format('precision', $precision);
    }

    /**
     * @param string $databaseType
     * @param string $scale
     * @return string
     */
    private function getScale(string $databaseType, string $scale): string
    {
        if (!in_array($databaseType, self::DECIMAL_TYPES)) {
            return '';
        }
        if (!$scale) {
            //as mysql default
            $scale = '0';
        }
        return $this->format('scale', $scale);
    }

    /**
     * @param string $in
     * @return string
     */
    private function getStringBoolean(string $in): string
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

    private function format($token, $value)
    {
        return $token . '="' . $value . '" ';
    }
}
