<?php

/**
 * Copyright © 2023 Lingaro sp. z o.o. All rights reserved.
 * See LICENSE for license details.
 */

declare(strict_types=1);

namespace Lingaro\Magento2Codegen\Service\Input;

use InvalidArgumentException;
use Symfony\Component\Console\Input\InputInterface;

interface ValidatorInterface
{
    /**
     * @throws InvalidArgumentException
     */
    public function validate(InputInterface $input): bool;
}
