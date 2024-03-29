<?php

/**
 * Copyright © 2023 Lingaro sp. z o.o. All rights reserved.
 * See LICENSE for license details.
 */

declare(strict_types=1);

namespace Lingaro\Magento2Codegen\Service;

class TemplateList
{
    private TemplateDir $templateDir;

    public function __construct(TemplateDir $templateDir)
    {
        $this->templateDir = $templateDir;
    }

    /**
     * @return string[]
     */
    public function getAll(): array
    {
        $templates = $this->templateDir->getTemplateNames();
        sort($templates);
        return $templates;
    }
}
