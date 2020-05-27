<?php
declare(strict_types=1);

namespace Orba\Magento2Codegen\Util\Magento\Config;

use Braintree\Exception;
use \JsonException;


/**
 * Class Csv
 *
 * @property  CsvI18n
 *
 */
class CsvI18n
{
    /** @var string */
    private $csv;

    /**
     * Csv constructor.
     * @param $csv
     */
    public function __construct(string $csv)
    {
        $this->csv = $csv;
    }

    /**
     * Merge Csv files
     *
     * @param string $csv
     * @return array
     * @throws Exception
     */
    public function merge(string $csv): array
    {
        $firstFile = $this->buildArray($this->csv);
        $secondFile = $this->buildArray($csv);

        $this->validateCsvFiles([$firstFile, $secondFile]);

        foreach ($secondFile as $secFile) {
            if ($secFile === false) {
                continue;
            }

            if (!$this->compareRows($firstFile,$secFile)) {
                $firstFile[] = $secFile;
            }
        }

        return $firstFile;
    }

    /**
     * @param array $array
     * @param array $row
     * @return bool
     */
    private function compareRows(array &$array, array $row): bool
    {
        $found = false;
        foreach ($array as $key => $file) {
            if ($file[0] === $row[0]) {
                $array[$key][1] = $row[1];
                $found = true;
                break;
            }
        }

        return $found;
    }

    /**
     * Save Csv File
     * @param array $data
     * @return string
     * @throws JsonException
     */
    public function saveCsv(array $data): string
    {
        return $this->generateCsv($data);
    }

    /**
     * Build Csv File from array
     * @param array $data
     * @param string $delimiter
     * @param string $enclosure
     * @return string
     */
    private function generateCsv(array $data, $delimiter = ',', $enclosure = '"'): string
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
     */
    private function buildArray(string $content): array
    {
        $data = str_getcsv($content, "\n");

        foreach ($data as &$item) {
            $item = str_getcsv($item);
        }

        return $data;
    }

    /**
     * @param array $files
     * @return bool
     * @throws Exception
     */
    private function validateCsvFiles(array $files): bool
    {
        foreach ($files as $file) {
            $this->validateCsv($file);
        }

        return true;
    }

    /**
     * @param array $file
     * @return bool
     */
    private function validateCsv(array $file): bool
    {
        array_walk($file, [$this, 'validate']);

        return true;
    }

    /**
     * @param $item
     * @return bool
     * @throws Exception
     */
    private function validate($item): bool
    {
        if (count($item) !== 2) {
            throw new Exception('Bad form I18n CSV file');
        }

        return true;
    }

}
