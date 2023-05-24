<?php

/**
 * Copyright © 2023 Lingaro sp. z o.o. All rights reserved.
 * See LICENSE for license details.
 */

declare(strict_types=1);

namespace Lingaro\Magento2Codegen\Service\Twig;

use Lingaro\Magento2Codegen\Service\StringFilter\FilterInterface;
use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;

class FiltersExtension extends AbstractExtension
{
    /**
     * @var FilterInterface[]
     */
    private array $filters;

    /**
     * @param FilterInterface[] $filters
     */
    public function __construct(array $filters = [])
    {
        $this->filters = $filters;
    }

    /**
     * @return TwigFilter[]
     */
    public function getFilters(): array
    {
        $filters = [];
        foreach ($this->filters as $name => $object) {
            $filters[] = new TwigFilter($name, [$object, 'filter']);
        }
        return $filters;
    }
}
