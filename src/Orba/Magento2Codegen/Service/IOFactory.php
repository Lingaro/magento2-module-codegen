<?php

namespace Orba\Magento2Codegen\Service;

use Orba\Magento2Codegen\Helper\IO;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class IOFactory
{
    public function create(InputInterface $input, OutputInterface $output): IO
    {
        return new IO($input, $output);
    }
}