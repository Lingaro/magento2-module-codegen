<?php

namespace Orba\Magento2Codegen\Service\TemplateType;

use Orba\Magento2Codegen\Model\Template;
use Orba\Magento2Codegen\Util\PropertyBag;

interface TypeInterface
{
    /**
     * Task that will be run before template generation.
     * If false is returned, code won't be generated.
     * @param Template $template
     * @return bool
     */
    public function beforeGenerationCommand(Template $template): bool;

    public function getBasePropertyBag(): PropertyBag;
}
