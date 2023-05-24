<?php

/**
 * Copyright © 2023 Lingaro sp. z o.o. All rights reserved.
 * See LICENSE for license details.
 */

declare(strict_types=1);

namespace Orba\Magento2Codegen\Service;

use Orba\Magento2Codegen\Service\Twig\EscaperExtension\EscaperCollection;
use Orba\Magento2Codegen\Service\Twig\FiltersExtension;
use Orba\Magento2Codegen\Service\Twig\FunctionsExtension;
use Orba\Magento2Codegen\Util\PropertyBag;
use Twig\Environment;
use Twig\Extension\EscaperExtension;
use Twig\Extension\SandboxExtension;
use Twig\Extension\StringLoaderExtension;
use Twig\Loader\ArrayLoader;
use Twig\Sandbox\SecurityPolicy;

use function array_merge;

class TwigTemplateProcessor implements TemplateProcessorInterface
{
    private const ALLOWED_TAGS = ['if', 'for', 'set'];
    private const ALLOWED_FILTERS = [
        'escape', 'upper', 'lower', 'raw', 'split', 'join', 'map', 'trim', 'last', 'replace'
    ];
    private const TEMPLATE_NAME = 'template';
    private const ALLOWED_FUNCTIONS = ['include', 'template_from_string'];

    private FiltersExtension $filtersExtension;
    private FunctionsExtension $functionsExtension;
    private EscaperCollection $escaperCollection;

    public function __construct(
        FiltersExtension $filtersExtension,
        FunctionsExtension $functionsExtension,
        EscaperCollection $escaperCollection
    ) {
        $this->filtersExtension = $filtersExtension;
        $this->functionsExtension = $functionsExtension;
        $this->escaperCollection = $escaperCollection;
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
        /** @var EscaperExtension $escaperExtension */
        $escaperExtension = $twig->getExtension(EscaperExtension::class);
        foreach ($this->escaperCollection->getItems() as $strategy => $object) {
            $escaperExtension->setEscaper($strategy, [$object, 'escape']);
        }
        $customFilters = [];
        foreach ($this->filtersExtension->getFilters() as $filter) {
            $customFilters[] = $filter->getName();
        }
        $customFunctions = [];
        foreach ($this->functionsExtension->getFunctions() as $function) {
            $customFunctions[] = $function->getName();
        }
        $twig->addExtension(new StringLoaderExtension());
        $twig->addExtension(
            new SandboxExtension(
                new SecurityPolicy(
                    self::ALLOWED_TAGS,
                    array_merge(self::ALLOWED_FILTERS, $customFilters),
                    [],
                    [],
                    array_merge(self::ALLOWED_FUNCTIONS, $customFunctions)
                ),
                true
            )
        );
        return $twig;
    }
}
