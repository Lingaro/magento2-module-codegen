<?php

/**
 * Copyright © 2023 Lingaro sp. z o.o. All rights reserved.
 * See LICENSE for license details.
 */

declare(strict_types=1);

namespace Orba\Magento2Codegen\Service;

use Orba\Magento2Codegen\Util\PropertyBag;

interface TemplateProcessorInterface
{
    public function replacePropertiesInText(string $text, PropertyBag $properties): string;
}
