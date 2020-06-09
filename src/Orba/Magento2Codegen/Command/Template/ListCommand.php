<?php

namespace Orba\Magento2Codegen\Command\Template;

use Orba\Magento2Codegen\Command\AbstractCommand;
use Orba\Magento2Codegen\Service\IO;
use Orba\Magento2Codegen\Service\TemplateFile;
use Orba\Magento2Codegen\Service\TemplateList;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class ListCommand extends AbstractCommand
{
    /**
     * @var TemplateList
     */
    private $templateList;

    /** @var TemplateFile  */
    private $templateFile;

    public function __construct(IO $io, TemplateList $templateList, TemplateFile $templateFile, array $inputValidators = [])
    {
        $this->templateList = $templateList;
        $this->templateFile = $templateFile;
        parent::__construct($io, $inputValidators);
    }

    public function configure()
    {
        $this
            ->setName('template:list')
            ->setDescription('Show list of possible templates to generate code.')
            ->setHelp("This command checks all available templates to generate code from.");
    }

    protected function _execute(InputInterface $input, OutputInterface $output): void
    {
        $this->io->getInstance()->writeln('Templates List');
        $templates = $this->templateList->getAll();
        if ($templates) {
            $this->io->getInstance()->newLine();
            foreach ($templates as $templateName) {
                if ($this->templateFile->getIsAbstract($templateName)) {
                    continue;
                }
                $this->io->getInstance()->writeln('<info>  ' . $templateName . '</info>');
            }
        }
    }
}
