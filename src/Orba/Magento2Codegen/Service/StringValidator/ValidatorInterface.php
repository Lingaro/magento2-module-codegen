<?php

/**
 * @copyright Copyright © 2022 Orba. All rights reserved.
 * @author    info@orba.co
 */

namespace Orba\Magento2Codegen\Service\StringValidator;

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
