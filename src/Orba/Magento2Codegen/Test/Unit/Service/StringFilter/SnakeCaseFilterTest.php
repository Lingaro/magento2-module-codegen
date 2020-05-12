<?php

namespace Orba\Magento2Codegen\Test\Unit\Service\StringFilter;

use Orba\Magento2Codegen\Service\StringFilter\SnakeCaseFilter;
use Orba\Magento2Codegen\Test\Unit\TestCase;

class SnakeCaseFilterTest extends TestCase
{
    /**
     * @var SnakeCaseFilter
     */
    private $snakeCaseFilter;

    public function setUp(): void
    {
        $this->snakeCaseFilter = new SnakeCaseFilter();
    }

    /**
     * @dataProvider filterProvider
     */
    public function testFilterReturnsCorrectlyFilteredString(string $text, string $expected): void
    {
        $result = $this->snakeCaseFilter->filter($text);
        $this->assertSame($expected, $result);
    }

    public function filterProvider(): array
    {
        return [
            ['one', 'one'],
            ['ONE', 'one'],
            ['one_two', 'one_two'],
            ['one-two', 'one_two'],
            ['one TWO', 'one_two'],
            ['oneTwo', 'one_two'],
            ['one1two2', 'one1two2'],
            ['one1Two2', 'one1_two2'],
            ['_one_', 'one'],
            ['one__two', 'one_two'],
            [' one  two ', 'one_two']
        ];
    }
}