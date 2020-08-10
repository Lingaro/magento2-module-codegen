<?php

namespace Orba\Magento2Codegen\Service;

use UnexpectedValueException;

class ArrayMerger
{
    /**
     * @return array
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
                } else {
                    if (!$canMergeBase || !$canMergeAppend) {
                        throw new UnexpectedValueException('Can\'t merge non array into array');
                    }
                    elseif (is_numeric($key)) {
                        if (!in_array($value, $base)) {
                            $base[] = $value;
                        }
                    } else {
                        $base[$key] = $value;
                    }
                }
            }
        }
        return $base;
    }
}
