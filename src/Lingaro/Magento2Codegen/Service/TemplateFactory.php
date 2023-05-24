<?php

/**
 * Copyright © 2023 Lingaro sp. z o.o. All rights reserved.
 * See LICENSE for license details.
 */

declare(strict_types=1);

namespace Orba\Magento2Codegen\Service;

use InvalidArgumentException;
use Orba\Magento2Codegen\Model\Template;

class TemplateFactory
{
    private TemplateFile $templateFile;
    private TemplateBuilder $templateBuilder;

    public function __construct(TemplateFile $templateFile, TemplateBuilder $templateBuilder)
    {
        $this->templateFile = $templateFile;
        $this->templateBuilder = $templateBuilder;
    }

    public function create(string $templateName): Template
    {
        if (!$this->templateFile->exists($templateName)) {
            throw new InvalidArgumentException(sprintf('Template does not exist: %s', $templateName));
        }
        $template = new Template();
        $this->templateBuilder
            ->addName($template, $templateName)
            ->addDescription($template)
            ->addPropertiesConfig($template)
            ->addAfterGenerateConfig($template)
            ->addDependencies($template, $this)
            ->addTypeService($template)
            ->addIsAbstract($template);
        return $template;
    }
}
