<?php

/**
 * @copyright Copyright © 2021 Orba. All rights reserved.
 * @author    info@orba.co
 */

declare(strict_types=1);

namespace Orba\Magento2Codegen\Service;

use function array_map;
use function explode;
use function fclose;
use function feof;
use function fopen;
use function fputcsv;
use function fread;
use function preg_replace;
use function preg_replace_callback;
use function preg_split;
use function rewind;
use function rtrim;
use function str_replace;
use function urldecode;
use function urlencode;
use function utf8_encode;

class CsvConverter
{
    /**
     * @see http://phpcoderweb.com/manual/function-str-getcsv_8085.html
     * @SuppressWarnings(PHPMD.BooleanArgumentFlag)
     */
    public function csvToArray(
        string $string,
        string $delimiter = ',',
        bool $skipEmptyLines = true,
        bool $trimFields = true
    ): array {
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
