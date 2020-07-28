<?php

namespace Orba\Magento2Codegen\Test\Unit\Service\StringFilter;

use Orba\Magento2Codegen\Service\StringFilter\KebabCaseFilter;
use Orba\Magento2Codegen\Test\Unit\TestCase;

class KebabCaseFilterTest extends TestCase
{
    /**
     * @var KebabCaseFilter
     */
    private $kebabCaseModifier;

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
