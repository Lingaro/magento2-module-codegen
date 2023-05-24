<?php

/**
 * Copyright © 2023 Lingaro sp. z o.o. All rights reserved.
 * See LICENSE for license details.
 */

declare(strict_types=1);

namespace Orba\Magento2Codegen\Test\Unit\Service\StringFilter;

use Orba\Magento2Codegen\Service\StringFilter\PluralizeFilter;
use Orba\Magento2Codegen\Test\Unit\TestCase;

class PluralizeFilterTest extends TestCase
{
    private PluralizeFilter $pluralizeFilter;

    public function setUp(): void
    {
        $this->pluralizeFilter = new PluralizeFilter();
    }

    /**
     * @dataProvider filterProvider
     */
    public function testFilterReturnsCorrectlyFilteredString(string $text, string $expected): void
    {
        $result = $this->pluralizeFilter->filter($text);
        $this->assertSame($expected, $result);
    }

    public function filterProvider(): array
    {
        return [
            ['city', 'cities'],
            ['child', 'children'],
            ['words', 'words'],
            ['flower', 'flowers'],
            ['PascalCity', 'PascalCities'],
            ['camelCity', 'camelCities'],
            ['Sheep', 'Sheep'],
            ['kebab-octopus', 'kebab-octopuses'],
            ['snake_plane', 'snake_planes'],
            ['Children', 'Children'],
            ['Line', 'Lines'],
            ['cinemA', 'cinemAs'],
            ['door', 'doors'],
        ];
    }
}
