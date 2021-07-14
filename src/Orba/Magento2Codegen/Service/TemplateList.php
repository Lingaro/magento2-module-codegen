<?php

/**
 * @copyright Copyright © 2021 Orba. All rights reserved.
 * @author    info@orba.co
 */

declare(strict_types=1);

namespace Orba\Magento2Codegen\Service;

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
