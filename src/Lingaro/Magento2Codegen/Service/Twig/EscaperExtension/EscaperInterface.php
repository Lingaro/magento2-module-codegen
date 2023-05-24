<?php

/**
 * Copyright © 2023 Lingaro sp. z o.o. All rights reserved.
 * See LICENSE for license details.
 */

declare(strict_types=1);

namespace Orba\Magento2Codegen\Service\Twig\EscaperExtension;

use Twig\Environment;

interface EscaperInterface
{
    public function escape(Environment $env, string $string, string $charset): string;
}
