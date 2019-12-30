<?php

namespace Orba\Magento2Codegen\Service;

use Orba\Magento2Codegen\Service\Twig\FiltersExtension;
use Orba\Magento2Codegen\Service\Twig\TwigToSchema;
use Orba\Magento2Codegen\Util\TemplatePropertyBag;
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
     * @var TwigToSchema
     */
    private $twigToSchema;

    /**
     * @var FiltersExtension
     */
    private $filtersExtension;

    public function __construct(TwigToSchema $twigToSchema, FiltersExtension $filtersExtension)
    {
        $this->twigToSchema = $twigToSchema;
        $this->filtersExtension = $filtersExtension;
    }

    public function getPropertiesInText(string $text): array
    {
        $properties = [];
        $schema = $this->twigToSchema->infer($this->getTwigEnvironment($text), self::TEMPLATE_NAME);
        foreach ($schema as $name => $attributes) {
            switch ($attributes['type']) {
                case 'scalar':
                    $properties[$name] = null;
                    break;
                case 'array':
                    $properties[$name] = $attributes['elements'];
                    break;
                default:
                    break;
            }
        }
        return $properties;
    }

    public function replacePropertiesInText(string $text, TemplatePropertyBag $properties): string
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