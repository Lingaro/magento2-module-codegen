<?php

/**
 * @copyright Copyright © 2021 Orba. All rights reserved.
 * @author    info@orba.co
 */

declare(strict_types=1);

namespace Orba\Magento2Codegen\Test\Unit\Service\StringFilter;

use Orba\Magento2Codegen\Service\StringFilter\PascalCaseFilter;
use Orba\Magento2Codegen\Test\Unit\TestCase;

class PascalCaseFilterTest extends TestCase
{
    private PascalCaseFilter $pascalCaseFilter;

    public function setUp(): void
    {
        $this->pascalCaseFilter = new PascalCaseFilter();
    }

    /**
     * @dataProvider filterProvider
     */
    public function testFilterReturnsCorrectlyFilteredString(string $text, string $expected): void
    {
        $result = $this->pascalCaseFilter->filter($text);
        $this->assertSame($expected, $result);
    }

    public function filterProvider(): array
    {
        return [
            ['one', 'One'],
            ['One', 'One'],
            ['oneTwo', 'OneTwo'],
            ['one two', 'OneTwo'],
            ['one_two', 'OneTwo'],
            ['one TWO', 'OneTwo'],
            ['one1two2', 'One1two2'],
            ['_one_', 'One'],
            ['one__two', 'OneTwo'],
            [' one  two ', 'OneTwo'],
            ['ModelNToN', 'ModelNToN'],
            ['superXMLMerger', 'SuperXmlMerger']
        ];
    }
}
