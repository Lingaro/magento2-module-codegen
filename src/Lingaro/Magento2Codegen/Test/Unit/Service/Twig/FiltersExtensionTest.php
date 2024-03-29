<?php

/**
 * Copyright © 2023 Lingaro sp. z o.o. All rights reserved.
 * See LICENSE for license details.
 */

declare(strict_types=1);

namespace Lingaro\Magento2Codegen\Test\Unit\Service\Twig;

use Lingaro\Magento2Codegen\Service\StringFilter\FilterInterface;
use Lingaro\Magento2Codegen\Service\Twig\FiltersExtension;
use Lingaro\Magento2Codegen\Test\Unit\TestCase;
use Twig\TwigFilter;

class FiltersExtensionTest extends TestCase
{
    public function testGetFiltersReturnsEmptyArrayIfFiltersAreNotDefined(): void
    {
        $result = (new FiltersExtension())->getFilters();
        $this->assertSame([], $result);
    }

    public function testGetFiltersReturnsArrayOfTwigFiltersIfFiltersAreDefined(): void
    {
        $result = (new FiltersExtension([
            'foo' => $this->getMockBuilder(FilterInterface::class)->getMockForAbstractClass()
        ]))->getFilters();
        $this->assertCount(1, $result);
        $this->assertContainsOnlyInstancesOf(TwigFilter::class, $result);
    }
}
