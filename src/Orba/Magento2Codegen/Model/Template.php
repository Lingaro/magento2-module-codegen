<?php

namespace Orba\Magento2Codegen\Model;

use Orba\Magento2Codegen\Service\TemplateType\TypeInterface;

class Template
{
    /**
     * @var string|null
     */
    private $name;

    /**
     * @var string|null
     */
    private $description;

    /**
     * @var Template[]
     */
    private $dependencies = [];

    /**
     * @var string|null
     */
    private $afterGenerateConfig;

    /**
     * @var array
     */
    private $propertiesConfig = [];

    /**
     * @var TypeInterface|null
     */
    private $typeService;

    public function getName():? string
    {
        return $this->name;
    }

    public function setName(string $name): Template
    {
        $this->name = $name;
        return $this;
    }

    public function getDescription():? string
    {
        return $this->description;
    }

    public function setDescription(string $description): Template
    {
        $this->description = $description;
        return $this;
    }

    public function getDependencies(): array
    {
        return $this->dependencies;
    }

    public function setDependencies(array $dependencies): Template
    {
        $this->dependencies = $dependencies;
        return $this;
    }

    public function getAfterGenerateConfig():? string
    {
        return $this->afterGenerateConfig;
    }

    public function setAfterGenerateConfig(string $afterGenerateConfig): Template
    {
        $this->afterGenerateConfig = $afterGenerateConfig;
        return $this;
    }

    public function getPropertiesConfig(): array
    {
        return $this->propertiesConfig;
    }

    public function setPropertiesConfig(array $propertiesConfig): Template
    {
        $this->propertiesConfig = $propertiesConfig;
        return $this;
    }

    public function getTypeService():? TypeInterface
    {
        return $this->typeService;
    }

    public function setTypeService(TypeInterface $typeService): Template
    {
        $this->typeService = $typeService;
        return $this;
    }
}
