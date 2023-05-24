<?php

/**
 * Copyright © 2023 Lingaro sp. z o.o. All rights reserved.
 * See LICENSE for license details.
 */

namespace Lingaro\Magento2Codegen\Service;

use Lingaro\Magento2Codegen\Service\StringValidator\ValidatorInterface;
use InvalidArgumentException;

class StringValidator
{
    /**
     * @var array<string, ValidatorInterface>
     */
    private array $validators;

    /**
     * @param array<string, ValidatorInterface> $validators
     */
    public function __construct(array $validators = [])
    {
        $this->validators = $validators;
    }

    /**
     * @return string[]
     */
    public function getAllowedValidators(): array
    {
        return array_keys($this->validators);
    }

    /**
     * @param string $value
     * @param array $requestedConditions
     * @throws InvalidArgumentException;
     */
    public function validate(string $value, array $requestedConditions): void
    {
        foreach ($requestedConditions as $validatorKey => $condition) {
            if (!isset($this->validators[$validatorKey])) {
                throw new InvalidArgumentException(
                    'Requested validation rule ' . $validatorKey . ' is missing.'
                );
            }
            if (!($this->validators[$validatorKey] instanceof ValidatorInterface)) {
                throw new InvalidArgumentException(
                    'Validation rule ' . $validatorKey . ' should implement ValidatorInterface'
                );
            }
            $this->validators[$validatorKey]->validate($value, $condition);
        }
    }
}
