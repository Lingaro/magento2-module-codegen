<?php

namespace Orba\Magento2Codegen\Command\Template;

use Orba\Magento2Codegen\Service\IO;
use Orba\Magento2Codegen\Service\TemplateList;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class ListCommand extends Command
{
    /**
     * @var IO
     */
    private $io;

    /**
     * @var TemplateList
     */
    private $templateList;

    public function __construct(IO $io, TemplateList $templateList)
    {
        $this->io = $io;
        $this->templateList = $templateList;
        parent::__construct();
    }

    public function configure()
    {
        $this
            ->setName('template:list')
            ->setDescription('Show list of possible templates to generate code.')
            ->setHelp("This command checks all available templates to generate code from.");
    }

    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->io->getInstance()->writeln('Templates List');
        $templates = $this->templateList->getAll();
        if ($templates) {
            $this->io->getInstance()->newLine();
            foreach ($templates as $templateName) {
                $this->io->getInstance()->writeln('<info>  ' . $templateName . '</info>');
            }
        }
    }
}