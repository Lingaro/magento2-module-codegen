<?php

namespace Orba\Magento2Codegen\Command\Php;

use Orba\Magento2Codegen\Service\IO;
use Orba\Magento2Codegen\Service\CommandUtil\Template;
use Orba\Magento2Codegen\Service\TemplateFile;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Orba\Magento2Codegen\Service\FileMerger\PhpMerger;

class MergeCommand extends Command
{
    /** @var PhpMerger */
    private $phpMerger;

    public function __construct(PhpMerger $phpMerger, string $name = null)
    {
        parent::__construct($name);
        $this->phpMerger = $phpMerger;
    }

    public function configure()
    {
        $this
            ->setName('file:php:merge')
            ->setDescription('X')
            ->setHelp("Y");
    }

    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $file1 = BP . "/" . "class1.php";
        $file2 = BP . "/" . "class2.php";
        $c1 = \file_get_contents($file1);
        $c2 = \file_get_contents($file2);

        $this->phpMerger->merge($c1, $c2);
    }


}