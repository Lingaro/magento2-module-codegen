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
    /** @var */
    private $found = false;

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
     */
    public function merge(string $csv): array
    {
        $firstFile = $this->buildArray($this->csv);
        $secondFile = $this->buildArray($csv);

        $this->validateCsvFiles([$firstFile,$secondFile]);

        foreach ($secondFile as $secFile) {
            $found = false;
            if ($secFile === false) {
                continue;
            }

            foreach ($firstFile as $key => $file) {
                if ($file[0] === $secFile[0]) {
                    $firstFile[$key][1] = $secFile[1];
                    $found = true;
                    break;
                }
            }
            if (!$found && $secFile) {
                $firstFile[] = $secFile;
            }
        }

        return $firstFile;
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

    private function validateCsvFiles(array $files){
        foreach ($files as $file){
            $this->validate($file);
        }
    }

    private function validate(array $file)
    {
        array_walk($file,[$this,'validateCsv']);
    }

    private function validateCsv($item,$key){
        if (count($item) !== 2){
            throw new Exception('Bad form I18n CSV file');
        }
    }

}
