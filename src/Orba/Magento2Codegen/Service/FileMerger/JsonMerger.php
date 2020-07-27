<?php

namespace Orba\Magento2Codegen\Service\FileMerger;

use Exception;
use Orba\Magento2Codegen\Service\ArrayMerger;

/**
 * Class JsonMerger
 * @package Orba\Magento2Codegen\Service\FileMerger
 */
class JsonMerger extends AbstractMerger implements MergerInterface
{
    /**
     * @var ArrayMerger
     */
    private $arrayMergeService;

    /**
     * @param ArrayMerger $arrayMergeService
     */
    public function __construct(ArrayMerger $arrayMergeService)
    {
        $this->arrayMergeService = $arrayMergeService;
    }

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

        $content = $this->arrayMergeService->arrayMergeRecursiveDistinct($oldContent, $newContent);
        $content = json_encode($content, JSON_PRETTY_PRINT + JSON_FORCE_OBJECT + JSON_UNESCAPED_SLASHES);

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
            throw new Exception("An error occurred while parsing string: " . json_last_error_msg());
        }
        return $jsonArray;
    }
}
