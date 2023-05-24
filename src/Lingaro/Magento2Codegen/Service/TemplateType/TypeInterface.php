<?php

/**
 * Copyright © 2023 Lingaro sp. z o.o. All rights reserved.
 * See LICENSE for license details.
 */

declare(strict_types=1);

namespace Orba\Magento2Codegen\Service\TemplateType;

use Orba\Magento2Codegen\Model\Template;
use Orba\Magento2Codegen\Util\PropertyBag;

interface TypeInterface
{
    /**
     * Task that will be run before template generation.
     * If false is returned, code won't be generated.
     */
    public function beforeGenerationCommand(Template $template): bool;

    public function getBasePropertyBag(): PropertyBag;
}
