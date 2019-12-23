<?php

namespace Orba\Magento2Codegen;

use Orba\Magento2Codegen\Service\IO;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class Application extends \Symfony\Component\Console\Application
{
    const CONFIG_FOLDER = 'config';
    const DEFAULT_PROPERTIES_FILENAME = 'default-properties.yml';

    /**
     * @var IO
     */
    private $io;

    public function __construct(IO $io, string $name = 'UNKNOWN', string $version = 'UNKNOWN')
    {
        parent::__construct($name, $version);
        $this->io = $io;
    }

    protected function configureIO(InputInterface $input, OutputInterface $output)
    {
        parent::configureIO($input, $output);
        $this->io->init($input, $output);
    }
}