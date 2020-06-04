<?php

namespace Orba\Magento2Codegen\Service;

class CsvConverter
{
    /**
     * @see http://phpcoderweb.com/manual/function-str-getcsv_8085.html
     * @param $string
     * @param string $delimiter
     * @param bool $skipEmptyLines
     * @param bool $trimFields
     * @return array
     */
    public function csvToArray($string, $delimiter = ",", $skipEmptyLines = true, $trimFields = true): array
    {
        $enc = $skipEmptyLines ? rtrim($string) : $string;
        $enc = preg_replace('/(?<!")""/', '!!Q!!', $enc);
        $enc = preg_replace_callback(
            '/"(.*?)"/s',
            function ($field) {
                return urlencode(utf8_encode($field[1]));
            },
            $enc
        );
        $lines = preg_split($skipEmptyLines ? ($trimFields ? '/( *\R)+/s' : '/\R+/s') : '/\R/s', $enc);
        return array_map(
            function ($line) use ($delimiter, $trimFields) {
                $fields = $trimFields ? array_map('trim', explode($delimiter, $line)) : explode($delimiter, $line);
                return array_map(
                    function ($field) {
                        return str_replace('!!Q!!', '"', utf8_decode(urldecode($field)));
                    },
                    $fields
                );
            },
            $lines
        );
    }

    /**
     * @param array $data
     * @param string $delimiter
     * @param string $enclosure
     * @return string
     */
    public function arrayToCsv(array $data, string $delimiter = ',', string $enclosure = '"'): string
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
}
