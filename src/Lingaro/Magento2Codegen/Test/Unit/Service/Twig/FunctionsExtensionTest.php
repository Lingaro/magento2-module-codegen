<?php

/**
 * Copyright © 2023 Lingaro sp. z o.o. All rights reserved.
 * See LICENSE for license details.
 */

declare(strict_types=1);

namespace Orba\Magento2Codegen\Test\Unit\Service\Twig;

use Orba\Magento2Codegen\Service\StringFunction\FunctionInterface;
use Orba\Magento2Codegen\Service\Twig\FunctionsExtension;
use Orba\Magento2Codegen\Test\Unit\TestCase;
use Twig\TwigFunction;

class FunctionsExtensionTest extends TestCase
{
    public function testGetFunctionsReturnsEmptyArrayIfFunctionsAreNotDefined(): void
    {
        $result = (new FunctionsExtension())->getFunctions();
        $this->assertSame([], $result);
    }

    public function testGetFunctionsReturnsArrayOfTwigFunctionsIfFunctionsAreDefined(): void
    {
        $result = (new FunctionsExtension([
            'foo' => $this->getMockBuilder(FunctionInterface::class)->getMockForAbstractClass()
        ]))->getFunctions();
        $this->assertCount(1, $result);
        $this->assertContainsOnlyInstancesOf(TwigFunction::class, $result);
    }
}
