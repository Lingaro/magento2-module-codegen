<?php

/**
 * @copyright Copyright © 2021 Orba. All rights reserved.
 * @author    info@orba.co
 */

declare(strict_types=1);

namespace Orba\Magento2Codegen\Util\Symfony;

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
    private ArrayInput $input;

    public function __construct(Command $command, ArrayInput $input, StreamOutput $output)
    {
        $this->command = $command;
        $this->input = $input;
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
            $this->input->setStream(self::createStream($this->inputs));
        }

        if (isset($options['interactive'])) {
            $this->input->setInteractive($options['interactive']);
        }

        $this->output->setDecorated($options['decorated'] ?? false);
        if (isset($options['verbosity'])) {
            $this->output->setVerbosity($options['verbosity']);
        }

        return $this->command->run($this->input, $this->output);
    }
}
