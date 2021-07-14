<?php

/**
 * @copyright Copyright © 2021 Orba. All rights reserved.
 * @author    info@orba.co
 */

declare(strict_types=1);

namespace Orba\Magento2Codegen\Service\Input;

use InvalidArgumentException;
use Symfony\Component\Console\Input\InputInterface;

interface ValidatorInterface
{
    /**
     * @throws InvalidArgumentException
     */
    public function validate(InputInterface $input): bool;
}
