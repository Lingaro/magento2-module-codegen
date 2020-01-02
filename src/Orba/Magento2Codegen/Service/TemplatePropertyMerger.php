<?php

namespace Orba\Magento2Codegen\Service;

use InvalidArgumentException;

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
            } else if (is_array($value)) {
                if (!array_key_exists($key, $arr)) {
                    $arr[$key] = $value;
                } else {
                    if (is_null($arr[$key])) {
                        $this->throwMixedPropertyTypesException();
                    }
                    $arr[$key] = array_unique(array_merge($arr[$key], $value));
                }
            }
        }
        return $arr;
    }

    private function throwMixedPropertyTypesException(): void
    {
        throw new InvalidArgumentException('Unable to merge properties of different types.');
    }
}