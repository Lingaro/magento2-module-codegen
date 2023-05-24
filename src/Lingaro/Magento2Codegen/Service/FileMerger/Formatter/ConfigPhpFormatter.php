<?php

/**
 * Copyright © 2023 Lingaro sp. z o.o. All rights reserved.
 * See LICENSE for license details.
 */

declare(strict_types=1);

namespace Orba\Magento2Codegen\Service\FileMerger\Formatter;

use function array_keys;
use function count;
use function implode;
use function is_array;
use function range;
use function sprintf;
use function str_repeat;
use function var_export;

/**
 * Copy of original magento class \Magento\Framework\App\DeploymentConfig\Writer\PhpFormatter
 * used for deployment configurations formatting
 *
 * A formatter for deployment configuration that presents it as a PHP-file that returns data
 */
class ConfigPhpFormatter implements FormatterInterface
{
    /**
     * 4 space indentation for array formatting.
     */
    private const INDENT = '    ';

    /**
     * Format deployment configuration.
     *
     * If $comments is present, each item will be added
     * as comment to the corresponding section
     */
    public function format(array $data, array $comments = []): string
    {
        if (!empty($comments) && is_array($data)) {
            return "<?php\nreturn [\n" . $this->formatData($data, $comments) . "\n];\n";
        }
        return "<?php\nreturn " . $this->varExportShort($data, 1) . ";\n";
    }

    /**
     * Format supplied data
     *
     * @param string[] $data
     * @param string[] $comments
     */
    private function formatData(array $data, array $comments = [], string $prefix = '    '): string
    {
        $elements = [];

        if (is_array($data)) {
            foreach ($data as $key => $value) {
                if (!empty($comments[$key])) {
                    $elements[] = $prefix . '/**';
                    $elements[] = $prefix . ' * For the section: ' . $key;

                    foreach (explode("\n", $comments[$key]) as $commentLine) {
                        $elements[] = $prefix . ' * ' . $commentLine;
                    }

                    $elements[] = $prefix . " */";
                }

                if (is_array($value)) {
                    $elements[] = $prefix . $this->varExportShort($key) . ' => [';
                    $elements[] = $this->formatData($value, [], '    ' . $prefix);
                    $elements[] = $prefix . '],';
                    continue;
                }
                $elements[] = $prefix . $this->varExportShort($key) . ' => ' . $this->varExportShort($value) . ',';
            }
            return implode("\n", $elements);
        }

        return var_export($data, true);
    }

    /**
     * Format generated config files using the short array syntax.
     *
     * If variable to export is an array, format with the php >= 5.4 short array syntax. Otherwise use
     * default var_export functionality.
     *
     * @param mixed $var
     */
    private function varExportShort($var, int $depth = 0): string
    {
        if (null === $var) {
            return 'null';
        } elseif (!is_array($var)) {
            return var_export($var, true);
        }

        $indexed = array_keys($var) === range(0, count($var) - 1);
        $expanded = [];
        foreach ($var as $key => $value) {
            $expanded[] = str_repeat(self::INDENT, $depth)
                . ($indexed ? '' : $this->varExportShort($key) . ' => ')
                . $this->varExportShort($value, $depth + 1);
        }

        return sprintf("[\n%s\n%s]", implode(",\n", $expanded), str_repeat(self::INDENT, $depth - 1));
    }
}
