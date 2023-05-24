<?php

/**
 * Copyright © 2023 Lingaro sp. z o.o. All rights reserved.
 * See LICENSE for license details.
 */

declare(strict_types=1);

namespace Orba\Magento2Codegen\Command;

use Exception;
use Orba\Magento2Codegen\Service\Input\ValidatorInterface;
use Orba\Magento2Codegen\Service\IO;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

abstract class AbstractCommand extends Command
{
    protected IO $io;
    protected array $inputValidators;

    public function __construct(IO $io, array $inputValidators = [])
    {
        $this->io = $io;
        $this->inputValidators = $inputValidators;
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        try {
            /** @var ValidatorInterface $validator */
            foreach ($this->inputValidators as $validator) {
                $validator->validate($input);
            }
            $this->executeInternal($input, $output);
            return 0;
        } catch (Exception $e) {
            $this->io->getInstance()->error($e->getMessage());
            return is_numeric($e->getCode()) ? (int) $e->getCode() : 1;
        }
    }

    abstract protected function executeInternal(InputInterface $input, OutputInterface $output): void;
}
