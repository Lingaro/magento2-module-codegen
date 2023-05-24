<?php

/**
 * Copyright © 2023 Lingaro sp. z o.o. All rights reserved.
 * See LICENSE for license details.
 */

declare(strict_types=1);

namespace Lingaro\Magento2Codegen\Service;

use InvalidArgumentException;
use Lingaro\Magento2Codegen\Model\InputPropertyInterface;

class PropertyStringValidatorsAdder
{
    private StringValidator $stringValidator;

    public function __construct(StringValidator $stringValidator)
    {
        $this->stringValidator = $stringValidator;
    }

    /**
     * @throws InvalidArgumentException
     */
    public function execute(InputPropertyInterface $property, array $config): void
    {
        if (isset($config['validators'])) {
            if (!is_array($config['validators'])) {
                throw new InvalidArgumentException('Validators must be an array.');
            }

            $allowedValidators = $this->stringValidator->getAllowedValidators();

            foreach (array_keys($config['validators']) as $key) {
                if (!is_string($key)) {
                    throw new InvalidArgumentException('All keys in validators array must be strings.');
                }

                if (!in_array($key, $allowedValidators)) {
                    throw new InvalidArgumentException(
                        'Validator "' . $key . '" is not allowed. The list of available validators is: "'
                            . implode(', ', $allowedValidators) . '".'
                    );
                }
            }
            $property->setValidators($config['validators']);
        }
    }
}
