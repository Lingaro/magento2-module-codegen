<?php

namespace Orba\Magento2Codegen\Test\Unit\Service\StringFilter;

use Orba\Magento2Codegen\Service\StringFilter\CapitalCaseFilter;
use Orba\Magento2Codegen\Test\Unit\TestCase;

/**
 * Class CapitalCaseFilterTest
 * @package Orba\Magento2Codegen\Test\Unit\Service\StringFilter
 */
class CapitalCaseFilterTest extends TestCase
{

    private $capitalCaseFilter;

    protected function setUp(): void
    {
        $this->capitalCaseFilter = new CapitalCaseFilter();
    }

    /**
     * @dataProvider filterProvider
     * @param string $text
     * @param string $expected
     */
    public function testFilterReturnsCorrectlyFilteredString(string $text, string $expected): void
    {
        $result = $this->capitalCaseFilter->filter($text);
        self::assertSame($expected, $result);
    }

    /**
     * @return array
     */
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