<?php

/**
 * Copyright © 2023 Lingaro sp. z o.o. All rights reserved.
 * See LICENSE for license details.
 */

declare(strict_types=1);

namespace Lingaro\Magento2Codegen\Service\TemplateType;

use Lingaro\Magento2Codegen\Model\Template;
use Lingaro\Magento2Codegen\Service\CommandUtil\Module as CommandUtil;
use Lingaro\Magento2Codegen\Service\TemplateFactory;
use Lingaro\Magento2Codegen\Util\PropertyBag;
use RuntimeException;

use function is_null;

class Module implements TypeInterface
{
    public const MODULE_TEMPLATE_NAME = 'module';

    private CommandUtil $commandUtil;
    private ?TemplateFactory $templateFactory = null;

    public function __construct(CommandUtil $commandUtil)
    {
        $this->commandUtil = $commandUtil;
    }

    public function beforeGenerationCommand(Template $template): bool
    {
        if (is_null($this->templateFactory)) {
            throw new RuntimeException('Template factory must be set.');
        }
        if ($this->commandUtil->shouldCreateModule()) {
            $this->commandUtil->createModule($this->templateFactory);
        }
        return true;
    }

    public function getBasePropertyBag(): PropertyBag
    {
        return $this->commandUtil->getPropertyBag();
    }

    public function setTemplateFactory(TemplateFactory $templateFactory): void
    {
        $this->templateFactory = $templateFactory;
    }
}
