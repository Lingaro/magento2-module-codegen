<?php

/**
 * @copyright Copyright © 2021 Orba. All rights reserved.
 * @author    info@orba.co
 */

declare(strict_types=1);

namespace Orba\Magento2Codegen\Test\Unit\Service\StringFilter;

use Orba\Magento2Codegen\Service\StringFilter\CapitalCaseFilter;
use Orba\Magento2Codegen\Test\Unit\TestCase;

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
