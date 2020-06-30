<?php

namespace Orba\Magento2Codegen\Service;

use InvalidArgumentException;
use Orba\Magento2Codegen\Model\Template;
use Orba\Magento2Codegen\Service\TemplateType\TypeInterface;
use RuntimeException;

class TemplateBuilder
{
    /**
     * @var TemplateFile
     */
    private $templateFile;

    /**
     * @var TypeInterface[]
     */
    private $templateTypes;

    public function __construct(TemplateFile $templateFile, array $templateTypes = [])
    {
        $this->templateFile = $templateFile;
        $this->templateTypes = $templateTypes;
    }

    public function addName(Template $template, string $name): TemplateBuilder
    {
        $template->setName($name);
        return $this;
    }

    public function addDescription(Template $template): TemplateBuilder
    {
        $this->validateNameExistence($template);
        $template->setDescription($this->templateFile->getDescription($template->getName()));
        return $this;
    }

    public function addPropertiesConfig(Template $template): TemplateBuilder
    {
        $this->validateNameExistence($template);
        $template->setPropertiesConfig($this->templateFile->getPropertiesConfig($template->getName()));
        return $this;
    }

    public function addAfterGenerateConfig(Template $template): TemplateBuilder
    {
        $this->validateNameExistence($template);
        $template->setAfterGenerateConfig($this->templateFile->getAfterGenerateConfig($template->getName()));
        return $this;
    }

    public function addDependencies(Template $template, TemplateFactory $templateFactory): TemplateBuilder
    {
        $this->validateNameExistence($template);
        $dependencyNames = $this->templateFile->getDependencies($template->getName(), true);
        $dependencies = [];
        foreach ($dependencyNames as $dependencyName) {
            if (!is_scalar($dependencyName)) {
                throw new InvalidArgumentException('Invalid dependencies array');
            }
            $dependencies[] = $templateFactory->create($dependencyName);
        }
        $template->setDependencies($dependencies);
        return $this;
    }

    public function addTypeService(Template $template): TemplateBuilder
    {
        $this->validateNameExistence($template);
        $type = $this->templateFile->getType($template->getName());
        if (!$type) {
            throw new InvalidArgumentException('Type service must be defined.');
        }
        if (isset($this->templateTypes[$type])) {
            $template->setTypeService($this->templateTypes[$type]);
        } else {
            throw new InvalidArgumentException(sprintf('Invalid type service code: %s', $type));
        }
        return $this;
    }

    private function validateNameExistence(Template $template): void
    {
        if (!$template->getName()) {
            throw new RuntimeException('Template name must be set first.');
        }
    }
}
