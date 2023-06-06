<?php

/**
 * Copyright © 2023 Lingaro sp. z o.o. All rights reserved.
 * See LICENSE for license details.
 */

declare(strict_types=1);

namespace Lingaro\Magento2Codegen\Service\FileMerger\CsvI18nMerger;

use InvalidArgumentException;
use Lingaro\Magento2Codegen\Service\CsvConverter;

class Processor
{
    private CsvConverter $csvConverter;

    public function __construct(CsvConverter $csvConverter)
    {
        $this->csvConverter = $csvConverter;
    }

    public function merge(string $initialContent, string $newContent): array
    {
        $destArr = $this->buildArray($initialContent);
        $sourceArr = $this->buildArray($newContent);

        foreach ($sourceArr as $row) {
            $key = $this->findKey($destArr, $row);
            if ($key) {
                $destArr[$key][1] = $row[1];
                continue;
            }
            $destArr[] = $row;
        }

        return $destArr;
    }

    public function generateContent(array $data): string
    {
        return $this->csvConverter->arrayToCsv($data);
    }

    private function findKey(array $array, array $row): ?int
    {
        foreach ($array as $key => $arr) {
            if ($arr[0] === $row[0]) {
                return $key;
            }
        }
        return null;
    }

    private function buildArray(string $content): array
    {
        $result = $this->csvConverter->csvToArray($content);
        $this->validate($result);
        return $result;
    }

    private function validate(array $data): void
    {
        foreach ($data as $row) {
            if (count($row) !== 2) {
                throw new InvalidArgumentException('I18n CSV file must contain exactly 2 columns.');
            }
        }
    }
}
