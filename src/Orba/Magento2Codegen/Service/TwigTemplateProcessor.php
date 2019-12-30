<?php

namespace Orba\Magento2Codegen\Service;

use Orba\Magento2Codegen\Service\Twig\TwigToSchema;
use Orba\Magento2Codegen\Util\TemplatePropertyBag;
use Twig\Environment;
use Twig\Extension\SandboxExtension;
use Twig\Loader\ArrayLoader;
use Twig\Sandbox\SecurityPolicy;

class TwigTemplateProcessor implements TemplateProcessorInterface
{
    const ALLOWED_TAGS = ['if', 'for'];
    const ALLOWED_FILTERS = ['escape', 'upper', 'lower'];
    const TEMPLATE_NAME = 'template';

    /**
     * @var TwigToSchema
     */
    private $twigToSchema;

    public function __construct(TwigToSchema $twigToSchema)
    {
        $this->twigToSchema = $twigToSchema;
    }

    public function getPropertiesInText(string $text): array
    {
        return array_keys(
            $this->twigToSchema->infer($this->createTwigEnvironment($text), self::TEMPLATE_NAME)
        );
    }

    public function replacePropertiesInText(string $text, TemplatePropertyBag $properties): string
    {
        $twig = $this->createTwigEnvironment($text);
        $twig->addExtension(
            new SandboxExtension(
                new SecurityPolicy(
                    self::ALLOWED_TAGS,self::ALLOWED_FILTERS, [], [], []
                ),
                true
            )
        );
        return $twig->render(self::TEMPLATE_NAME, $properties->toArray());
    }

    private function createTwigEnvironment(string $text): Environment
    {
        $loader = new ArrayLoader([self::TEMPLATE_NAME => $text]);
        return new Environment($loader);
    }
}