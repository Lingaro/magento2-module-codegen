<?php

namespace Orba\Magento2Codegen\Service\PropertyValueCollector;

use InvalidArgumentException;
use Orba\Magento2Codegen\Model\ArrayProperty;
use Orba\Magento2Codegen\Model\PropertyInterface;
use RuntimeException;

class ArrayCollector extends AbstractInputCollector
{
    private const ITEM_PROPERTY = 'item';

    /**
     * @var CollectorFactory
     */
    private $collectorFactory;

    public function setCollectorFactory(CollectorFactory $collectorFactory): void
    {
        $this->collectorFactory = $collectorFactory;
    }

    protected function validateProperty(PropertyInterface $property): void
    {
        if (!$property instanceof ArrayProperty) {
            throw new InvalidArgumentException('Invalid property type.');
        }
    }

    protected function collectValueFromInput(PropertyInterface $property)
    {
        if (is_null($this->collectorFactory)) {
            throw new RuntimeException('Collector factory is unset.');
        }
        /** @var ArrayProperty $property */
        $items = [];
        $i = 0;

        $promptPattern = 'Do you want to add an item to "%s" array?';
        while ($this->isQuestionForced($property, $i) || $this->io->getInstance()->confirm(
            sprintf($promptPattern, $property->getName()), true
        )) {
            $item = [];
            $dependencies = [];
            foreach ($property->getChildren() as $child) {
                $collector = $this->collectorFactory->create($child);
                $questionPrefix = $this->questionPrefix . $property->getName() . '.' . $i . '.';
                if ($collector instanceof AbstractInputCollector) {
                    $collector->setQuestionPrefix($questionPrefix);
                }
                if ($child->getDepend()) {
                    foreach ($child->getDepend() as $propertyKey => $propertyValue) {
                        switch ($this->getDependencyScope($propertyKey)) {
                            case self::ITEM_PROPERTY:
                                $propertyKey = $questionPrefix . $this->getPropertyName($propertyKey);
                                break;
                        }
                        if (!isset($dependencies[$propertyKey]) || $dependencies[$propertyKey] != $propertyValue) {
                            continue 2;
                        }
                    }
                }
                $item[$child->getName()] = $collector->collectValue($child);
                $dependencies[$questionPrefix . $child->getName()] = $item[$child->getName()];
            }
            $items[] = $item;
            $i++;
            $promptPattern = 'Do you want to add another item to "%s" array?';
        };

        return $items;
    }

    /**
     * @param string $propertyKey
     * @return string
     */
    private function getDependencyScope(string $propertyKey): string
    {
        $exploded = explode('.', $propertyKey);

        if (count($exploded) > 1) {
            $type = $exploded[0];
            switch ($type) {
                case self::ITEM_PROPERTY:
                    return self::ITEM_PROPERTY;
                default:
                    throw new RuntimeException("Unknown dependency scope: " . $type);
            }
        }

        throw new RuntimeException("Could not determine dependency scope from: " . $propertyKey);
    }

    /**
     * @param string $propertyKey
     * @return string
     */
    private function getPropertyName(string $propertyKey): string
    {
        $scope = $this->getDependencyScope($propertyKey);
        switch ($scope) {
            case self::ITEM_PROPERTY:
                return str_replace(self::ITEM_PROPERTY . '.', '', $propertyKey);
        }
    }

    private function isQuestionForced(PropertyInterface $property, int $i): bool
    {
        if ($i > 0) {
            return false;
        }
        return $property->getRequired();
    }
}
