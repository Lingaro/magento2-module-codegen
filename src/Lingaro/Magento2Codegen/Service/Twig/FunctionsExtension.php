<?php

/**
 * Copyright © 2023 Lingaro sp. z o.o. All rights reserved.
 * See LICENSE for license details.
 */

namespace Orba\Magento2Codegen\Service\Twig;

use Orba\Magento2Codegen\Service\StringFunction\FunctionInterface;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class FunctionsExtension extends AbstractExtension
{
    /**
     * @var FunctionInterface[]
     */
    private array $functions;

    /**
     * @param FunctionInterface[] $functions
     */
    public function __construct(array $functions = [])
    {
        $this->functions = $functions;
    }

    /**
     * @return TwigFunction[]
     */
    public function getFunctions(): array
    {
        $functions = [];
        foreach ($this->functions as $functionName => $functionObj) {
            $functions[] = new TwigFunction($functionName, [$functionObj, 'execute']);
        }
        return $functions;
    }
}
