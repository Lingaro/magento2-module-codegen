<?php

/**
 * @copyright Copyright © 2021 Orba. All rights reserved.
 * @author    info@orba.co
 */

declare(strict_types=1);

namespace Orba\Magento2Codegen\Service;

use InvalidArgumentException;
use Orba\Magento2Codegen\Model\Template;
use Orba\Magento2Codegen\Service\TemplateType\TypeInterface;
use RuntimeException;

use function is_scalar;

class TemplateBuilder
{
    private TemplateFile $templateFile;

    /**
     * @var TypeInterface[]
     */
    private array $templateTypes;

    public function __construct(TemplateFile $templateFile, array $templateTypes = [])
    {
        $this->templateFile = $templateFile;
        $this->templateTypes = $templateTypes;
    }

    public function addName(Template $template, string $name): self
    {
        $template->setName($name);
        return $this;
    }

    public function addDescription(Template $template): self
    {
        $this->validateNameExistence($template);
        $template->setDescription($this->templateFile->getDescription($template->getName()));
        return $this;
    }

    public function addPropertiesConfig(Template $template): self
    {
        $this->validateNameExistence($template);
        $template->setPropertiesConfig($this->templateFile->getPropertiesConfig($template->getName()));
        return $this;
    }

    public function addAfterGenerateConfig(Template $template): self
    {
        $this->validateNameExistence($template);
        $template->setAfterGenerateConfig($this->templateFile->getAfterGenerateConfig($template->getName()));
        return $this;
    }

    public function addDependencies(Template $template, TemplateFactory $templateFactory): self
    {
        $this->validateNameExistence($template);
        $dependencyNames = $this->templateFile->getDependencies($template->getName());
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

    public function addTypeService(Template $template): self
    {
        $this->validateNameExistence($template);
        $type = $this->templateFile->getType($template->getName());
        if (!$type) {
            throw new InvalidArgumentException('Type service must be defined.');
        }
        if (!isset($this->templateTypes[$type])) {
            throw new InvalidArgumentException(sprintf('Invalid type service code: %s', $type));
        }
        $template->setTypeService($this->templateTypes[$type]);
        return $this;
    }

    public function addIsAbstract(Template $template): self
    {
        $this->validateNameExistence($template);
        $template->setIsAbstract(
            $this->templateFile->getIsAbstract($template->getName())
        );
        return $this;
    }

    private function validateNameExistence(Template $template): void
    {
        if (!$template->getName()) {
            throw new RuntimeException('Template name must be set first.');
        }
    }
}
