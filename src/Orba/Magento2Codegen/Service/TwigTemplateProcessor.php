<?php

namespace Orba\Magento2Codegen\Service;

use Orba\Magento2Codegen\Service\Twig\FiltersExtension;
use Orba\Magento2Codegen\Util\PropertyBag;
use Twig\Environment;
use Twig\Extension\SandboxExtension;
use Twig\Loader\ArrayLoader;
use Twig\Sandbox\SecurityPolicy;
use Twig\TwigFilter;

class TwigTemplateProcessor implements TemplateProcessorInterface
{
    const ALLOWED_TAGS = ['if', 'for'];
    const ALLOWED_FILTERS = ['escape', 'upper', 'lower'];
    const TEMPLATE_NAME = 'template';

    /**
     * @var FiltersExtension
     */
    private $filtersExtension;

    public function __construct(FiltersExtension $filtersExtension)
    {
        $this->filtersExtension = $filtersExtension;
    }

    public function replacePropertiesInText(string $text, PropertyBag $properties): string
    {
        return $this->getTwigEnvironment($text)->render(self::TEMPLATE_NAME, $properties->toArray());
    }

    private function getTwigEnvironment(string $text): Environment
    {
        $loader = new ArrayLoader([self::TEMPLATE_NAME => $text]);
        $twig = new Environment($loader);
        $twig->addExtension($this->filtersExtension);
        $customFilters = [];
        foreach ($this->filtersExtension->getFilters() as $filter) {
            /** @var TwigFilter $filter */
            $customFilters[] = $filter->getName();
        }
        $twig->addExtension(
            new SandboxExtension(
                new SecurityPolicy(
                    self::ALLOWED_TAGS, array_merge(self::ALLOWED_FILTERS, $customFilters), [], [], []
                ),
                true
            )
        );
        return $twig;
    }
}