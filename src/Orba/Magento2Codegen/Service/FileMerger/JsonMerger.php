<?php

/**
 * @copyright Copyright © 2021 Orba. All rights reserved.
 * @author    info@orba.co
 */

declare(strict_types=1);

namespace Orba\Magento2Codegen\Service\FileMerger;

use Exception;
use Orba\Magento2Codegen\Service\ArrayMerger;

use function json_decode;
use function json_encode;
use function json_last_error;
use function json_last_error_msg;

class JsonMerger extends AbstractMerger implements MergerInterface
{
    private ArrayMerger $arrayMergeService;

    public function __construct(ArrayMerger $arrayMergeService)
    {
        $this->arrayMergeService = $arrayMergeService;
    }

    public function merge(
        string $oldContent,
        string $newContent,
        int $options = JSON_PRETTY_PRINT + JSON_UNESCAPED_SLASHES
    ): string {
        return json_encode(
            $this->arrayMergeService->arrayMergeRecursiveDistinct($this->parse($oldContent), $this->parse($newContent)),
            $options
        );
    }

    /**
     * @SuppressWarnings(PHPMD.ErrorControlOperator)
     */
    public function parse(string $jsonString): array
    {
        $jsonArray = @json_decode($jsonString, true);
        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new Exception('An error occurred while parsing string: ' . json_last_error_msg());
        }
        return $jsonArray;
    }
}
