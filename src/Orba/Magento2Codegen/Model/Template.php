<?php

/**
 * @copyright Copyright © 2021 Orba. All rights reserved.
 * @author    info@orba.co
 */

declare(strict_types=1);

namespace Orba\Magento2Codegen\Model;

use Orba\Magento2Codegen\Service\TemplateType\TypeInterface;

class Template
{
    private ?string $name = null;
    private ?string $description = null;
    private ?string $afterGenerateConfig = null;
    private array $propertiesConfig = [];
    private ?TypeInterface $typeService;
    private bool $isAbstract = false;

    /**
     * @var Template[]
     */
    private array $dependencies = [];

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;
        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;
        return $this;
    }

    public function getDependencies(): array
    {
        return $this->dependencies;
    }

    public function setDependencies(array $dependencies): self
    {
        $this->dependencies = $dependencies;
        return $this;
    }

    public function getAfterGenerateConfig(): ?string
    {
        return $this->afterGenerateConfig;
    }

    public function setAfterGenerateConfig(string $afterGenerateConfig): self
    {
        $this->afterGenerateConfig = $afterGenerateConfig;
        return $this;
    }

    public function getPropertiesConfig(): array
    {
        return $this->propertiesConfig;
    }

    public function setPropertiesConfig(array $propertiesConfig): self
    {
        $this->propertiesConfig = $propertiesConfig;
        return $this;
    }

    public function getTypeService(): ?TypeInterface
    {
        return $this->typeService;
    }

    public function setTypeService(TypeInterface $typeService): self
    {
        $this->typeService = $typeService;
        return $this;
    }

    public function getIsAbstract(): bool
    {
        return $this->isAbstract;
    }

    public function setIsAbstract(bool $isAbstract): self
    {
        $this->isAbstract = $isAbstract;
        return $this;
    }
}
