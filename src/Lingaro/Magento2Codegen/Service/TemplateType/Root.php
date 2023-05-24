<?php

/**
 * Copyright © 2023 Lingaro sp. z o.o. All rights reserved.
 * See LICENSE for license details.
 */

declare(strict_types=1);

namespace Lingaro\Magento2Codegen\Service\TemplateType;

use Lingaro\Magento2Codegen\Model\Template;
use Lingaro\Magento2Codegen\Service\CommandUtil\Root as RootCommandUtil;
use Lingaro\Magento2Codegen\Service\PropertyBagFactory;
use Lingaro\Magento2Codegen\Util\PropertyBag;
use RuntimeException;

class Root implements TypeInterface
{
    private PropertyBagFactory $propertyBagFactory;
    private RootCommandUtil $commandUtil;

    public function __construct(PropertyBagFactory $propertyBagFactory, RootCommandUtil $commandUtil)
    {
        $this->propertyBagFactory = $propertyBagFactory;
        $this->commandUtil = $commandUtil;
    }

    public function beforeGenerationCommand(Template $template): bool
    {
        if (!$this->commandUtil->isCurrentDirMagentoRoot()) {
            throw new RuntimeException('This template can be used only in the root Magento dir.');
        }
        return true;
    }

    public function getBasePropertyBag(): PropertyBag
    {
        return $this->propertyBagFactory->create();
    }
}
