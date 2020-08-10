<?php

namespace Orba\Magento2Codegen\Service;

use InvalidArgumentException;
use Orba\Magento2Codegen\Model\Template;

class TemplateFactory
{
    /**
     * @var TemplateFile
     */
    private $templateFile;

    /**
     * @var TemplateBuilder
     */
    private $templateBuilder;

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
