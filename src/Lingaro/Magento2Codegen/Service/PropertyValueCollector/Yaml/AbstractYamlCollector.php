<?php

/**
 * Copyright © 2023 Lingaro sp. z o.o. All rights reserved.
 * See LICENSE for license details.
 */

declare(strict_types=1);

namespace Lingaro\Magento2Codegen\Service\PropertyValueCollector\Yaml;

use Lingaro\Magento2Codegen\Model\InputPropertyInterface;
use Lingaro\Magento2Codegen\Model\PropertyInterface;
use Lingaro\Magento2Codegen\Service\PropertyValueCollector\AbstractCollector;
use Lingaro\Magento2Codegen\Util\PropertyBag;
use RuntimeException;

abstract class AbstractYamlCollector extends AbstractCollector
{
    private DataProviderRegistry $dataProviderRegistry;
    protected DataProvider $dataProvider;
    protected string $keyPrefix = '';

    public function __construct(DataProviderRegistry $dataProviderRegistry)
    {
        $this->dataProviderRegistry = $dataProviderRegistry;
    }

    public function setKeyPrefix(string $prefix): void
    {
        $this->keyPrefix = $prefix;
    }

    protected function internalCollectValue(PropertyInterface $property, PropertyBag $propertyBag)
    {
        $this->dataProvider = $this->dataProviderRegistry->get();
        if (!$property instanceof InputPropertyInterface) {
            throw new RuntimeException('Invalid property type.');
        }
        return $this->collectValueFromInput($property, $propertyBag);
    }

    /**
     * @return mixed
     */
    abstract protected function collectValueFromInput(InputPropertyInterface $property, PropertyBag $propertyBag);
}
