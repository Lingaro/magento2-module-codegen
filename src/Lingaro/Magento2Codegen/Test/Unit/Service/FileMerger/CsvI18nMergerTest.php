<?php

/**
 * Copyright © 2023 Lingaro sp. z o.o. All rights reserved.
 * See LICENSE for license details.
 */

declare(strict_types=1);

namespace Lingaro\Magento2Codegen\Test\Unit\Service\FileMerger;

use InvalidArgumentException;
use Lingaro\Magento2Codegen\Service\CsvConverter;
use Lingaro\Magento2Codegen\Service\FileMerger\CsvI18nMerger;
use Lingaro\Magento2Codegen\Test\Unit\TestCase;

class CsvI18nMergerTest extends TestCase
{
    private CsvI18nMerger $csvMerger;

    public function setUp(): void
    {
        $this->csvMerger = new CsvI18nMerger(new CsvI18nMerger\Processor(new CsvConverter()));
    }

    public function testMergeThrowsExceptionIfOldContentIsNotValidCsv(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->csvMerger->merge('foo', 'test,Test');
    }

    public function testMergeThrowsExceptionIfNewContentIsNotValidCsv(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->csvMerger->merge('test,Test', 'bar');
    }

    public function testMergeReturnsMergedCsvFile(): void
    {
        $result = $this->csvMerger->merge(
            'test1,Test 1' . PHP_EOL . 'test2,Test 2',
            'test2,Test2replaced' . PHP_EOL . 'test3,Test 3'
        );
        $expected = 'test1,"Test 1"' . PHP_EOL . 'test2,Test2replaced' . PHP_EOL . 'test3,"Test 3"' . PHP_EOL;
        $this->assertSame($expected, $result);
    }
}
