<?php

namespace Orba\Magento2Codegen\Service\Twig;

use Orba\Magento2Codegen\Service\StringFunction\FunctionInterface;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class FunctionsExtension extends AbstractExtension
{
    /**
     * @var FunctionInterface[]
     */
    private $functions;

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
