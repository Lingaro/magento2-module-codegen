<?php

namespace Orba\Magento2Codegen\Service\CommandUtil;

use Orba\Magento2Codegen\Model\ConstProperty;
use Orba\Magento2Codegen\Model\PropertyInterface;
use Orba\Magento2Codegen\Service\Config;
use Orba\Magento2Codegen\Service\PropertyFactory;
use Orba\Magento2Codegen\Service\PropertyValueCollector\CollectorFactory;
use Orba\Magento2Codegen\Service\TemplateFile;

class TemplateProperty
{
    /**
     * @var Config
     */
    private $config;

    /**
     * @var TemplateFile
     */
    private $templateFile;

    /**
     * @var CollectorFactory
     */
    private $propertyValueCollectorFactory;

    /**
     * @var PropertyFactory
     */
    private $propertyFactory;

    public function __construct(
        Config $config,
        TemplateFile $templateFile,
        CollectorFactory $propertyValueCollectorFactory,
        PropertyFactory $propertyFactory
    ) {
        $this->config = $config;
        $this->templateFile = $templateFile;
        $this->propertyValueCollectorFactory = $propertyValueCollectorFactory;
        $this->propertyFactory = $propertyFactory;
    }

    /**
     * @return PropertyInterface[]
     */
    public function collectConstProperties(): array
    {
        $properties = [];
        foreach ($this->config['defaultProperties'] as $defaultProperty) {
            $properties[$defaultProperty['name']] = $this->propertyFactory
                ->create(
                    $defaultProperty['name'],
                    ['type' => ConstProperty::TYPE, 'value' => $defaultProperty['value']]
                );
        }
        return $properties;
    }

    /**
     * @param string $template
     * @return PropertyInterface[]
     */
    public function collectInputProperties(string $template): array
    {
        $propertiesConfig = [];
        $templateNames = array_merge([$template], $this->templateFile->getDependencies($template, true));
        foreach ($templateNames as $templateName) {
            $propertiesConfig = array_merge($propertiesConfig, $this->templateFile->getPropertiesConfig($templateName));
        }
        $properties = [];
        foreach ($propertiesConfig as $propertyName => $propertyConfig) {
            $properties[$propertyName] = $this->propertyFactory->create($propertyName, $propertyConfig);
        }
        return $properties;
    }
}
