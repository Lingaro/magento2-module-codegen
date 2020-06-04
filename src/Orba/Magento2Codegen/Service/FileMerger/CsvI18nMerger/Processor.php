<?php
declare(strict_types=1);

namespace Orba\Magento2Codegen\Service\FileMerger\CsvI18nMerger;

use Exception;
use InvalidArgumentException;
use Orba\Magento2Codegen\Service\CsvConverter;

class Processor
{
    /**
     * @var CsvConverter
     */
    private $csvConverter;

    public function __construct(CsvConverter $csvConverter)
    {
        $this->csvConverter = $csvConverter;
    }

    /**
     * Merge Csv files
     *
     * @param string $initialContent
     * @param string $newContent
     * @return array
     * @throws Exception
     */
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

    /**
     * @param array $data
     * @return string
     */
    public function generateContent(array $data): string
    {
        return $this->csvConverter->arrayToCsv($data);
    }

    /**
     * @param array $array
     * @param array $row
     * @return int|null
     */
    private function findKey(array $array, array $row): ?int
    {
        foreach ($array as $key => $arr) {
            if ($arr[0] === $row[0]) {
                return $key;
            }
        }

        return null;
    }

    /**
     * Build array from Csv File
     * @param string $content
     * @return array
     * @throws Exception
     */
    private function buildArray(string $content): array
    {
        $result = $this->csvConverter->csvToArray($content);
        $this->validate($result);
        return $result;
    }

    /**
     * @param array $data
     * @return bool
     * @throws InvalidArgumentException
     */
    private function validate(array $data): bool
    {
        foreach ($data as $row) {
            if (count($row) !== 2) {
                throw new InvalidArgumentException('I18n CSV file must contain exactly 2 columns.');
            }
        }

        return true;
    }

}
