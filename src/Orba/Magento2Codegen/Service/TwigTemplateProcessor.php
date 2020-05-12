<?php

namespace Orba\Magento2Codegen\Service;

use Orba\Magento2Codegen\Service\Twig\FiltersExtension;
use Orba\Magento2Codegen\Service\Twig\FunctionsExtension;
use Orba\Magento2Codegen\Util\PropertyBag;
use Twig\Environment;
use Twig\Extension\SandboxExtension;
use Twig\Loader\ArrayLoader;
use Twig\Sandbox\SecurityPolicy;
use Twig\TwigFilter;

class TwigTemplateProcessor implements TemplateProcessorInterface
{
    const ALLOWED_TAGS = ['if', 'for'];
    const ALLOWED_FILTERS = ['escape', 'upper', 'lower', 'raw'];
    const TEMPLATE_NAME = 'template';
    public const ALLOWED_FUNCTIONS = ['columnDefinition', 'databaseTypeToPHP'];

    /**
     * @var FiltersExtension
     */
    private $filtersExtension;

    private $functionsExtension;

    public function __construct(FiltersExtension $filtersExtension, FunctionsExtension $functionsExtension)
    {
        $this->filtersExtension = $filtersExtension;
        $this->functionsExtension = $functionsExtension;
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
        $twig->addExtension($this->functionsExtension);
        $customFilters = [];
        foreach ($this->filtersExtension->getFilters() as $filter) {
            /** @var TwigFilter $filter */
            $customFilters[] = $filter->getName();
        }
        $twig->addExtension(
            new SandboxExtension(
                new SecurityPolicy(
                    self::ALLOWED_TAGS, array_merge(self::ALLOWED_FILTERS, $customFilters), [], [], self::ALLOWED_FUNCTIONS
                ),
                true
            )
        );
        return $twig;
    }
}