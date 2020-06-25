<?php

namespace Orba\Magento2Codegen\Service;

class TemplateList
{
    /**
     * @var TemplateDir
     */
    private $templateDir;

    public function __construct(TemplateDir $templateDir)
    {
        $this->templateDir = $templateDir;
    }

    /**
     * @return array
     */
    public function getAll()
    {
        $templates = $this->templateDir->getTemplateNames();
        sort($templates);
        return $templates;
    }
}
