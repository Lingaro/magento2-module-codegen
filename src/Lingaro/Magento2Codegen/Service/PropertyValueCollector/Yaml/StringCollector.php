<?php

/**
 * Copyright © 2023 Lingaro sp. z o.o. All rights reserved.
 * See LICENSE for license details.
 */

declare(strict_types=1);

namespace Lingaro\Magento2Codegen\Service\PropertyValueCollector\Yaml;

use InvalidArgumentException;
use Lingaro\Magento2Codegen\Exception\ValueNotSetException;
use Lingaro\Magento2Codegen\Model\InputPropertyInterface;
use Lingaro\Magento2Codegen\Model\StringProperty;
use Lingaro\Magento2Codegen\Service\StringValidator;
use Lingaro\Magento2Codegen\Util\PropertyBag;

class StringCollector extends AbstractYamlCollector
{
    protected string $propertyType = StringProperty::class;

    private StringValidator $stringValidator;

    public function __construct(DataProviderRegistry $dataProviderRegistry, StringValidator $stringValidator)
    {
        parent::__construct($dataProviderRegistry);
        $this->stringValidator = $stringValidator;
    }

    protected function collectValueFromInput(InputPropertyInterface $property, PropertyBag $propertyBag): string
    {
        $propertyValidators = $property->getValidators();
        $isRequired = $property->getRequired();
        $key = $this->keyPrefix . $property->getName();
        $value = (string) $this->dataProvider->get($key, $property->getDefaultValue());
        if ($isRequired && $value === '') {
            throw new ValueNotSetException(sprintf('Value of "%s" cannot be empty.', $key));
        }
        if ($value !== '' && $propertyValidators) {
            try {
                $this->stringValidator->validate($value, $propertyValidators);
            } catch (InvalidArgumentException $e) {
                throw new InvalidArgumentException(
                    sprintf('Validation failed for "%s": %s', $key, $e->getMessage())
                );
            }
        }
        return $value;
    }
}
