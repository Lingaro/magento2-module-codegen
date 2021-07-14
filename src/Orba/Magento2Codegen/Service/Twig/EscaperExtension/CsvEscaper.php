<?php

/**
 * @copyright Copyright © 2021 Orba. All rights reserved.
 * @author    info@orba.co
 */

declare(strict_types=1);

namespace Orba\Magento2Codegen\Service\Twig\EscaperExtension;

use Twig\Environment;

use function str_replace;

class CsvEscaper implements EscaperInterface
{
    public function escape(Environment $env, string $string, string $charset): string
    {
        return str_replace('"', '""', $string);
    }
}
