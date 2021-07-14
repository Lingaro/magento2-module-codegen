<?php

/**
 * @copyright Copyright © 2021 Orba. All rights reserved.
 * @author    info@orba.co
 */

declare(strict_types=1);

namespace Orba\Magento2Codegen\Test\Unit\Service\StringFilter;

use Orba\Magento2Codegen\Service\StringFilter\UcfirstFilter;
use Orba\Magento2Codegen\Test\Unit\TestCase;

class UcfirstFilterTest extends TestCase
{
    private UcfirstFilter $ucfirstFilter;

    public function setUp(): void
    {
        $this->ucfirstFilter = new UcfirstFilter();
    }

    /**
     * @dataProvider filterProvider
     */
    public function testFilterReturnsCorrectlyFilteredString(string $text, string $expected): void
    {
        $result = $this->ucfirstFilter->filter($text);
        $this->assertSame($expected, $result);
    }

    public function filterProvider(): array
    {
        return [
            ['one', 'One'],
            ['ONE', 'ONE'],
            ['one_two', 'One_two'],
            ['one-two', 'One-two'],
            ['one TWO', 'One TWO'],
            ['oneTwo', 'OneTwo'],
            ['one1two2', 'One1two2'],
            ['one1Two2', 'One1Two2'],
            ['_one_', '_one_'],
            ['one__two', 'One__two'],
            [' one  two ', ' one  two ']
        ];
    }
}
