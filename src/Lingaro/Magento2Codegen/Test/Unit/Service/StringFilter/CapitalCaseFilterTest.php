<?php

/**
 * Copyright © 2023 Lingaro sp. z o.o. All rights reserved.
 * See LICENSE for license details.
 */

declare(strict_types=1);

namespace Lingaro\Magento2Codegen\Test\Unit\Service\StringFilter;

use Lingaro\Magento2Codegen\Service\StringFilter\CapitalCaseFilter;
use Lingaro\Magento2Codegen\Test\Unit\TestCase;

class CapitalCaseFilterTest extends TestCase
{
    private CapitalCaseFilter $capitalCaseFilter;

    protected function setUp(): void
    {
        $this->capitalCaseFilter = new CapitalCaseFilter();
    }

    /**
     * @dataProvider filterProvider
     */
    public function testFilterReturnsCorrectlyFilteredString(string $text, string $expected): void
    {
        $result = $this->capitalCaseFilter->filter($text);
        self::assertSame($expected, $result);
    }

    public function filterProvider(): array
    {
        return [
            ['one', 'One'],
            ['One', 'One'],
            ['oneTwo', 'Onetwo'],
            ['one two', 'Onetwo'],
            ['one_two', 'Onetwo'],
            ['one TWO', 'Onetwo'],
            ['one1two2', 'One1two2'],
            ['_one_', 'One'],
            ['one__two', 'Onetwo'],
            [' one  two ', 'Onetwo']
        ];
    }
}
