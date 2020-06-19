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

        $content = array_merge_recursive($oldContent, $newContent);
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
}