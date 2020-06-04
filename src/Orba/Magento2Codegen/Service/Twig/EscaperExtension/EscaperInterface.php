<?php

namespace Orba\Magento2Codegen\Service\Twig\EscaperExtension;

use Twig\Environment;

interface EscaperInterface
{
    public function escape(Environment $env, string $string, string $charset): string;
}
