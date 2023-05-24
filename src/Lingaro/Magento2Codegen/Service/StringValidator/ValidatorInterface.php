<?php

/**
 * Copyright © 2023 Lingaro sp. z o.o. All rights reserved.
 * See LICENSE for license details.
 */

namespace Lingaro\Magento2Codegen\Service\StringValidator;

use InvalidArgumentException;

interface ValidatorInterface
{
    /**
     * In case if validation is not passed the InvalidArgumentException must be thrown
     * with proper error message
     *
     * @param mixed $condition
     * @throws InvalidArgumentException
     */
    public function validate(string $value, $condition): void;
}
