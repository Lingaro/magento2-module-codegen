<?php

/**
 * @copyright Copyright © 2021 Orba. All rights reserved.
 * @author    info@orba.co
 */

declare(strict_types=1);

namespace Orba\Magento2Codegen\Test\Unit\Service\Twig;

use Orba\Magento2Codegen\Service\StringFilter\FilterInterface;
use Orba\Magento2Codegen\Service\Twig\FiltersExtension;
use Orba\Magento2Codegen\Test\Unit\TestCase;
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
