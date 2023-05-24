<?php

/**
 * Copyright © 2023 Lingaro sp. z o.o. All rights reserved.
 * See LICENSE for license details.
 */

declare(strict_types=1);

namespace Lingaro\Magento2Codegen\Service;

use Lingaro\Magento2Codegen\Util\PropertyBag;

interface TemplateProcessorInterface
{
    public function replacePropertiesInText(string $text, PropertyBag $properties): string;
}
