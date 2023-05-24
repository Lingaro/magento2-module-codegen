<?php

/**
 * Copyright © 2023 Lingaro sp. z o.o. All rights reserved.
 * See LICENSE for license details.
 */

declare(strict_types=1);

namespace Lingaro\Magento2Codegen\Service\PropertyValueCollector\Console;

use Lingaro\Magento2Codegen\Model\ArrayProperty;
use Lingaro\Magento2Codegen\Model\InputPropertyInterface;
use Lingaro\Magento2Codegen\Service\IO;
use Lingaro\Magento2Codegen\Service\PropertyDependencyChecker;
use Lingaro\Magento2Codegen\Service\PropertyValueCollector\CollectorFactory;
use Lingaro\Magento2Codegen\Util\PropertyBag;
use RuntimeException;

use function is_null;
use function sprintf;

class ArrayCollector extends AbstractConsoleCollector
{
    private ?CollectorFactory $collectorFactory = null;
    private PropertyDependencyChecker $propertyDependencyChecker;

    protected string $propertyType = ArrayProperty::class;

    public function __construct(IO $io, PropertyDependencyChecker $propertyDependencyChecker)
    {
        parent::__construct($io);
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
        $i = 0;

        $promptPattern = 'Do you want to add an item to "%s" array?';
        while (
            $this->isQuestionForced($property, $i) || $this->io->getInstance()->confirm(
                sprintf($promptPattern, $property->getName()),
                true
            )
        ) {
            $item = [];
            foreach ($property->getChildren() as $child) {
                $collector = $this->collectorFactory->create('console', $child);
                $questionPrefix = $this->questionPrefix . $property->getName() . '.' . $i . '.';
                if ($collector instanceof AbstractConsoleCollector) {
                    $collector->setQuestionPrefix($questionPrefix);
                }
                if (
                    $child instanceof InputPropertyInterface
                    && $this->areDependencyConditionsMet($child, $propertyBag, $item)
                ) {
                    continue;
                }
                $item[$child->getName()] = $collector->collectValue($child, $propertyBag);
            }
            $items[] = $item;
            $i++;
            $promptPattern = 'Do you want to add another item to "%s" array?';
        };

        return $items;
    }

    private function isQuestionForced(InputPropertyInterface $property, int $i): bool
    {
        if ($i > 0) {
            return false;
        }
        return $property->getRequired();
    }

    private function areDependencyConditionsMet(
        InputPropertyInterface $property,
        PropertyBag $propertyBag,
        array $item
    ): bool {
        return !$this->propertyDependencyChecker->areRootConditionsMet($property, $propertyBag)
            || !$this->propertyDependencyChecker->areScopeConditionsMet('item', $property, $item);
    }
}
