<?php

/**
 * Copyright © 2023 Lingaro sp. z o.o. All rights reserved.
 * See LICENSE for license details.
 */

declare(strict_types=1);

namespace Lingaro\Magento2Codegen\Util\Symfony;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Output\StreamOutput;
use Symfony\Component\Console\Tester\TesterTrait;

/**
 * @see \Symfony\Component\Console\Tester\CommandTester
 */
class CommandTester
{
    use TesterTrait;

    private Command $command;
    private ArrayInput $inputArray;

    public function __construct(Command $command, ArrayInput $inputArray, StreamOutput $output)
    {
        $this->command = $command;
        $this->inputArray = $inputArray;
        $this->output = $output;
    }

    /**
     * Executes the command.
     *
     * Available execution options:
     *
     *  * interactive: Sets the input interactive flag
     *  * decorated:   Sets the output decorated flag
     *  * verbosity:   Sets the output verbosity flag
     *
     * @param array $options An array of execution options
     * @return int The command exit code
     */
    public function execute(array $options = []): int
    {
        if ($this->inputs) {
            $this->inputArray->setStream(self::createStream($this->inputs));
        }

        if (isset($options['interactive'])) {
            $this->inputArray->setInteractive($options['interactive']);
        }

        $this->output->setDecorated($options['decorated'] ?? false);
        if (isset($options['verbosity'])) {
            $this->output->setVerbosity($options['verbosity']);
        }

        return $this->command->run($this->input, $this->output);
    }
}
