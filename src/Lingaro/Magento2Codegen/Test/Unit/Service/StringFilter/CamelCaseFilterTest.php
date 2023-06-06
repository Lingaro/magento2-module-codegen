<?php

/**
 * Copyright © 2023 Lingaro sp. z o.o. All rights reserved.
 * See LICENSE for license details.
 */

declare(strict_types=1);

namespace Lingaro\Magento2Codegen\Test\Unit\Service\StringFilter;

use Lingaro\Magento2Codegen\Service\StringFilter\CamelCaseFilter;
use Lingaro\Magento2Codegen\Test\Unit\TestCase;

class CamelCaseFilterTest extends TestCase
{
    private CamelCaseFilter $camelCaseFilter;

    public function setUp(): void
    {
        $this->camelCaseFilter = new CamelCaseFilter();
    }

    /**
     * @dataProvider filterProvider
     */
    public function testFilterReturnsCorrectlyFilteredString(string $text, string $expected): void
    {
        $result = $this->camelCaseFilter->filter($text);
        $this->assertSame($expected, $result);
    }

    public function filterProvider(): array
    {
        return [
            ['one', 'one'],
            ['One', 'one'],
            ['oneTwo', 'oneTwo'],
            ['one two', 'oneTwo'],
            ['one_two', 'oneTwo'],
            ['one TWO', 'oneTwo'],
            ['one1two2', 'one1two2'],
            ['_one_', 'one'],
            ['one__two', 'oneTwo'],
            [' one  two ', 'oneTwo'],
            ['ModelNToN', 'modelNToN'],
            ['superXMLMerger', 'superXmlMerger']
        ];
    }
}
