<?php

/**
 * @copyright Copyright © 2022 Orba. All rights reserved.
 * @author    info@orba.co
 */

declare(strict_types=1);

namespace Orba\Magento2Codegen\Service\PropertyValueCollector\Yaml;

use Orba\Magento2Codegen\Exception\ValueInvalidException;
use Orba\Magento2Codegen\Exception\ValueNotSetException;
use Orba\Magento2Codegen\Model\ArrayProperty;
use Orba\Magento2Codegen\Model\InputPropertyInterface;
use Orba\Magento2Codegen\Service\PropertyDependencyChecker;
use Orba\Magento2Codegen\Service\PropertyValueCollector\CollectorFactory;
use Orba\Magento2Codegen\Util\PropertyBag;
use RuntimeException;

use function array_keys;
use function is_null;

class ArrayCollector extends AbstractYamlCollector
{
    protected string $propertyType = ArrayProperty::class;

    private ?CollectorFactory $collectorFactory = null;
    private PropertyDependencyChecker $propertyDependencyChecker;

    public function __construct(
        DataProviderRegistry $dataProviderRegistry,
        PropertyDependencyChecker $propertyDependencyChecker
    ) {
        parent::__construct($dataProviderRegistry);
        $this->propertyDependencyChecker = $propertyDependencyChecker;
    }

    public function setCollectorFactory(CollectorFactory $collectorFactory): void
    {
        $this->collectorFactory = $collectorFactory;
    }

    protected function collectValueFromInput(InputPropertyInterface $property, PropertyBag $propertyBag): array
    {
        if (is_null($this->collectorFactory)) {
            throw new RuntimeException('Collector factory is unset.');
        }
        /** @var ArrayProperty $property */
        $items = [];
        $key = $this->keyPrefix . $property->getName();
        $array = $this->dataProvider->get($key, $property->getDefaultValue());
        if ($array === null) {
            $array = [];
        }
        if ($property->getRequired() && empty($array)) {
            throw new ValueNotSetException(sprintf('Value of "%s" cannot be empty.', $key));
        }
        if (!is_array($array)) {
            throw new ValueInvalidException(sprintf('Value of "%s" must be an array.', $key));
        }
        foreach (array_keys($array) as $index) {
            $items[$index] = $this->collectItem($property, $index, $propertyBag);
        }
        return $items;
    }

    private function areDependencyConditionsMet(
        InputPropertyInterface $property,
        PropertyBag $propertyBag,
        array $item
    ): bool {
        return !$this->propertyDependencyChecker->areRootConditionsMet($property, $propertyBag)
            || !$this->propertyDependencyChecker->areScopeConditionsMet('item', $property, $item);
    }

    private function collectItem(ArrayProperty $property, int $index, PropertyBag $propertyBag): array
    {
        $item = [];
        foreach ($property->getChildren() as $child) {
            $collector = $this->collectorFactory->create('yaml', $child);
            $keyPrefix = $this->keyPrefix . $property->getName() . '.' . $index . '.';
            if ($collector instanceof AbstractYamlCollector) {
                $collector->setKeyPrefix($keyPrefix);
            }
            if (
                $child instanceof InputPropertyInterface
                && $this->areDependencyConditionsMet($child, $propertyBag, $item)
            ) {
                continue;
            }
            $item[$child->getName()] = $collector->collectValue($child, $propertyBag);
        }
        return $item;
    }
}
