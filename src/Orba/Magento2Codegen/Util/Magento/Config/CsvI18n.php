<?php
declare(strict_types=1);

namespace Orba\Magento2Codegen\Util\Magento\Config;

use \Exception;
use RuntimeException;


/**
 * Class Csv
 *
 * @property  CsvI18n
 *
 */
class CsvI18n
{
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
     * Generate Content from array
     * @param array $data
     * @param string $delimiter
     * @param string $enclosure
     * @return string
     */
    public function generateContent(array $data, $delimiter = ',', $enclosure = '"'): string
    {
        $handle = fopen('php://temp', 'r+');
        $contents = '';
        foreach ($data as $line) {
            fputcsv($handle, $line, $delimiter, $enclosure);
        }
        rewind($handle);
        while (!feof($handle)) {
            $contents .= fread($handle, 8192);
        }
        fclose($handle);

        return $contents;
    }

    /**
     * Build array from Csv File
     * @param string $content
     * @return array
     * @throws Exception
     */
    private function buildArray(string $content): array
    {
        $data = explode(PHP_EOL, $content);

        foreach ($data as &$item) {
            $item = str_getcsv($item);
        }

        $this->validate($data);

        return $data;
    }

    /**
     * @param array $data
     * @return bool
     */
    private function validate(array $data): bool
    {
        foreach ($data as $row) {
            if (count($row) !== 2) {
                throw new RuntimeException('I18n CSV file must contain exactly 2 columns.');
            }
        }

        return true;
    }

}
