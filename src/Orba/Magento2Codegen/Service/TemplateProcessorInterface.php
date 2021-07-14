<?php

/**
 * @copyright Copyright © 2021 Orba. All rights reserved.
 * @author    info@orba.co
 */

declare(strict_types=1);

namespace Orba\Magento2Codegen\Service;

use Orba\Magento2Codegen\Util\PropertyBag;

interface TemplateProcessorInterface
{
    public function replacePropertiesInText(string $text, PropertyBag $properties): string;
}
