<?php

namespace Orba\Magento2Codegen\Service\Input;

use InvalidArgumentException;
use Symfony\Component\Console\Input\InputInterface;

interface ValidatorInterface
{
    /**
     * @param InputInterface $input
     * @return bool
     * @throws InvalidArgumentException
     */
    public function validate(InputInterface $input): bool;
}