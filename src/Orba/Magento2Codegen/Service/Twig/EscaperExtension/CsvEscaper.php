<?php

namespace Orba\Magento2Codegen\Service\Twig\EscaperExtension;

use Twig\Environment;

class CsvEscaper implements EscaperInterface
{
    public function escape(Environment $env, string $string, string $charset): string
    {
        return str_replace('"', '""', $string);
    }
}
