<?php

namespace Orba\Magento2Codegen\Service\FileMerger;

use Exception;

/**
 * Class PhpMerger
 * @package Orba\Magento2Codegen\Service\FileMerger
 */
class JsonMerger extends AbstractMerger implements MergerInterface
{
    /**
     * @param string $oldContent
     * @param string $newContent
     * @return string
     * @throws Exception
     */
    public function merge(string $oldContent, string $newContent): string
    {
        $oldContent = $this->parse($oldContent);
        $newContent = $this->parse($newContent);

        $content = $this->arrayMergeRecursiveDistinct($oldContent, $newContent);
        $content = json_encode($content, JSON_PRETTY_PRINT);

        return $content;
    }

    /**
     * @param string $jsonString
     * @return array
     * @throws Exception
     */
    public function parse(string $jsonString): array
    {
        $jsonArray = @json_decode($jsonString, true);
        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new Exception("An error occured while parsing string " . json_last_error_msg());
        }
        return $jsonArray;
    }

    /**
     * @return array
     */
    private function arrayMergeRecursiveDistinct(): array
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
                if (!array_key_exists($key, $base) and !is_numeric($key)) {
                    $base[$key] = $append[$key];
                    continue;
                }
                if (is_array($value) || is_array($base[$key])) {
                    $base[$key] = $this->arrayMergeRecursiveDistinct($base[$key], $append[$key]);
                } else {
                    if (!$canMergeBase || !$canMergeAppend) {
                        throw new \UnexpectedValueException('Can\'t merge non array into array');
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