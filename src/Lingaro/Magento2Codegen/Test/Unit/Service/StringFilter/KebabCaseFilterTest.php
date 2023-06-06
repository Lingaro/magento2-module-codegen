<?php

/**
 * Copyright © 2023 Lingaro sp. z o.o. All rights reserved.
 * See LICENSE for license details.
 */

declare(strict_types=1);

namespace Lingaro\Magento2Codegen\Test\Unit\Service\StringFilter;

use Lingaro\Magento2Codegen\Service\StringFilter\KebabCaseFilter;
use Lingaro\Magento2Codegen\Test\Unit\TestCase;

class KebabCaseFilterTest extends TestCase
{
    private KebabCaseFilter $kebabCaseModifier;

    public function setUp(): void
    {
        $this->kebabCaseModifier = new KebabCaseFilter();
    }

    /**
     * @dataProvider filterProvider
     */
    public function testFilterReturnsCorrectlyFilteredString(string $text, string $expected): void
    {
        $result = $this->kebabCaseModifier->filter($text);
        $this->assertSame($expected, $result);
    }

    public function filterProvider(): array
    {
        return [
            ['one', 'one'],
            ['ONE', 'one'],
            ['one_two', 'one-two'],
            ['one-two', 'one-two'],
            ['one TWO', 'one-two'],
            ['oneTwo', 'one-two'],
            ['one1two2', 'one1two2'],
            ['one1Two2', 'one1-two2'],
            ['-one-', 'one'],
            ['one--two', 'one-two'],
            [' one  two ', 'one-two'],
            ['ModelNToN', 'model-n-to-n'],
            ['superXMLMerger', 'super-xml-merger']
        ];
    }
}
