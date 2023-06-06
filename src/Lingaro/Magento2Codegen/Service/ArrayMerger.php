<?php

/**
 * Copyright © 2023 Lingaro sp. z o.o. All rights reserved.
 * See LICENSE for license details.
 */

declare(strict_types=1);

namespace Lingaro\Magento2Codegen\Service;

use UnexpectedValueException;

use function array_key_exists;
use function array_shift;
use function func_get_args;
use function is_array;
use function is_numeric;

class ArrayMerger
{
    /**
     * @SuppressWarnings(PHPMD.NPathComplexity)
     * @SuppressWarnings(PHPMD.CyclomaticComplexity)
     */
    public function arrayMergeRecursiveDistinct(): array
    {
        $arrays = func_get_args();
        $base = array_shift($arrays);
        $canMergeBase = $canMergeAppend = true;
        if (!is_array($base)) {
            $base = empty($base) ? array() : array($base);
            $canMergeBase = false;
        }
        foreach ($arrays as $append) {
            if (!is_array($append)) {
                $append = array($append);
                $canMergeAppend = false;
            }
            foreach ($append as $key => $value) {
                if (!array_key_exists($key, $base) && !is_numeric($key)) {
                    $base[$key] = $append[$key];
                    continue;
                }
                if (isset($base[$key]) && (is_array($value) || is_array($base[$key]))) {
                    $base[$key] = $this->arrayMergeRecursiveDistinct($base[$key], $append[$key]);
                    continue;
                }
                if (!$canMergeBase || !$canMergeAppend) {
                    throw new UnexpectedValueException('Can\'t merge non array into array');
                }
                if (is_numeric($key)) {
                    if (!in_array($value, $base)) {
                        $base[] = $value;
                    }
                    continue;
                }
                $base[$key] = $value;
            }
        }
        return $base;
    }
}
