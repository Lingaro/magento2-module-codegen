<?php

namespace Orba\Magento2Codegen\Command;

use Exception;
use Orba\Magento2Codegen\Service\Input\ValidatorInterface;
use Orba\Magento2Codegen\Service\IO;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

abstract class AbstractCommand extends Command
{
    /**
     * @var IO
     */
    protected $io;

    /**
     * @var array
     */
    protected $inputValidators;

    public function __construct(IO $io, array $inputValidators = [])
    {
        $this->io = $io;
        $this->inputValidators = $inputValidators;
        parent::__construct();
    }

    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        try {
            /** @var ValidatorInterface $validator */
            foreach ($this->inputValidators as $validator) {
                $validator->validate($input);
            }
            $this->_execute($input, $output);
        } catch (Exception $e) {
            $this->io->getInstance()->error($e->getMessage());
        }
    }

    protected function _execute(InputInterface $input, OutputInterface $output): void
    {
    }
}