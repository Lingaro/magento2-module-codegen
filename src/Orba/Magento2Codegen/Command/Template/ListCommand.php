<?php

namespace Orba\Magento2Codegen\Command\Template;

use Orba\Magento2Codegen\Service\IOFactory;
use Orba\Magento2Codegen\Service\TemplateList;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class ListCommand extends Command
{
    /**
     * @var IOFactory
     */
    private $ioFactory;

    /**
     * @var TemplateList
     */
    private $templateList;

    public function __construct(IOFactory $ioFactory, TemplateList $templateList)
    {
        $this->ioFactory = $ioFactory;
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
        $io = $this->ioFactory->create($input, $output);
        $io->writeln('Templates List');

        $templates = $this->templateList->getAll();
        if ($templates) {
            $io->newLine();
            foreach ($templates as $templateName) {
                $io->writeln('<info>  ' . $templateName . '</info>');
            }
        }
    }
}