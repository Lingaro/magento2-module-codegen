<?php

/**
 * Copyright © 2023 Lingaro sp. z o.o. All rights reserved.
 * See LICENSE for license details.
 */

declare(strict_types=1);

namespace Orba\Magento2Codegen\Service;

use InvalidArgumentException;

use function array_key_exists;
use function array_merge;
use function array_unique;
use function is_array;
use function is_null;

class TemplatePropertyMerger
{
    public function merge(array $arr, array $arr2): array
    {
        foreach ($arr2 as $key => $value) {
            if (is_null($value)) {
                if (isset($arr[$key]) && is_array($arr[$key])) {
                    $this->throwMixedPropertyTypesException();
                }
                $arr[$key] = $value;
            } elseif (is_array($value)) {
                if (!array_key_exists($key, $arr)) {
                    $arr[$key] = $value;
                    continue;
                }
                if (is_null($arr[$key])) {
                    $this->throwMixedPropertyTypesException();
                }
                $arr[$key] = array_unique(array_merge($arr[$key], $value));
            }
        }
        return $arr;
    }

    private function throwMixedPropertyTypesException(): void
    {
        throw new InvalidArgumentException('Unable to merge properties of different types.');
    }
}
