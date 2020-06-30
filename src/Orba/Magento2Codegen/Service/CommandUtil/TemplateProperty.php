<?php

namespace Orba\Magento2Codegen\Service\CommandUtil;

use Orba\Magento2Codegen\Model\ConstProperty;
use Orba\Magento2Codegen\Model\PropertyInterface;
use Orba\Magento2Codegen\Model\Template as TemplateModel;
use Orba\Magento2Codegen\Service\Config;
use Orba\Magento2Codegen\Service\PropertyFactory;
use Orba\Magento2Codegen\Service\PropertyValueCollector\CollectorFactory;

class TemplateProperty
{
    /**
     * @var Config
     */
    private $config;

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
        CollectorFactory $propertyValueCollectorFactory,
        PropertyFactory $propertyFactory
    ) {
        $this->config = $config;
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
     * @param TemplateModel $template
     * @return PropertyInterface[]
     */
    public function collectInputProperties(TemplateModel $template): array
    {
        $propertiesConfig = [];
        foreach (array_merge([$template], $template->getDependencies()) as $item) {
            /** @var TemplateModel $item */
            $propertiesConfig = array_merge($propertiesConfig, $item->getPropertiesConfig());
        }
        $properties = [];
        foreach ($propertiesConfig as $propertyName => $propertyConfig) {
            $properties[$propertyName] = $this->propertyFactory->create($propertyName, $propertyConfig);
        }
        return $properties;
    }
}
