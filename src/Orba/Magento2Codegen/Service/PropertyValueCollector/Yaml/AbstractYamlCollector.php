<?php

/**
 * @copyright Copyright © 2022 Orba. All rights reserved.
 * @author    info@orba.co
 */

declare(strict_types=1);

namespace Orba\Magento2Codegen\Service\PropertyValueCollector\Yaml;

use Orba\Magento2Codegen\Model\InputPropertyInterface;
use Orba\Magento2Codegen\Model\PropertyInterface;
use Orba\Magento2Codegen\Service\PropertyValueCollector\AbstractCollector;
use Orba\Magento2Codegen\Util\PropertyBag;
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
