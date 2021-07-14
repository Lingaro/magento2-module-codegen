<?php

/**
 * @copyright Copyright © 2021 Orba. All rights reserved.
 * @author    info@orba.co
 */

declare(strict_types=1);

namespace Orba\Magento2Codegen\Service\Twig;

use Orba\Magento2Codegen\Service\StringFilter\FilterInterface;
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
