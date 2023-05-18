<?php

/**
 * Copyright © 2023 Lingaro sp. z o.o. All rights reserved.
 * See LICENSE for license details.
 */

declare(strict_types=1);

namespace Orba\Magento2Codegen\Service\TemplateType;

use Orba\Magento2Codegen\Model\Template;
use Orba\Magento2Codegen\Service\PropertyBagFactory;
use Orba\Magento2Codegen\Util\PropertyBag;

class Basic implements TypeInterface
{
    private PropertyBagFactory $propertyBagFactory;

    public function __construct(PropertyBagFactory $propertyBagFactory)
    {
        $this->propertyBagFactory = $propertyBagFactory;
    }

    public function beforeGenerationCommand(Template $template): bool
    {
        return true;
    }

    public function getBasePropertyBag(): PropertyBag
    {
        return $this->propertyBagFactory->create();
    }
}
