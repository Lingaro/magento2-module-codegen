<?php

namespace Orba\Magento2Codegen\Service\PropertyValueCollector;

use InvalidArgumentException;
use Orba\Magento2Codegen\Model\ArrayProperty;
use Orba\Magento2Codegen\Model\PropertyInterface;
use RuntimeException;

class ArrayCollector extends AbstractInputCollector
{
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
        do {
            $item = [];
            foreach ($property->getChildren() as $child) {
                $collector = $this->collectorFactory->create($child);
                if ($collector instanceof AbstractInputCollector) {
                    $collector->setQuestionPrefix($this->questionPrefix . $property->getName() . '.' . $i . '.');
                }
                $item[$child->getName()] = $collector->collectValue($child);
            }
            $items[] = $item;
            $i++;
        } while ($this->io->getInstance()->confirm(
            sprintf('Do you want to add another item to "%s" array?', $property->getName()), true
        ));
        return $items;
    }
}