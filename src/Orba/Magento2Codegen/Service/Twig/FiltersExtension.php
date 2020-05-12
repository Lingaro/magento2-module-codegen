<?php

namespace Orba\Magento2Codegen\Service\Twig;

use Orba\Magento2Codegen\Service\StringFilter\FilterInterface;
use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;

class FiltersExtension extends AbstractExtension
{
    /**
     * @var FilterInterface[]
     */
    private $filters;

    public function __construct(array $filters = [])
    {
        $this->filters = $filters;
    }

    public function getFilters(): array
    {
        $filters = [];
        foreach ($this->filters as $name => $object) {
            $filters[] = new TwigFilter($name, [$object, 'filter']);
        }
        return $filters;
    }
}