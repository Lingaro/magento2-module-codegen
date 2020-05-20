<?php

namespace Orba\Magento2Codegen\Command\Template;

use Orba\Magento2Codegen\Command\AbstractCommand;
use Orba\Magento2Codegen\Service\IO;
use Orba\Magento2Codegen\Service\CommandUtil\Template;
use Orba\Magento2Codegen\Service\TemplateFile;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class InfoCommand extends AbstractCommand
{
    /**
     * @var string|null
     */
    private $templateName;

    /**
     * @var Template
     */
    private $util;

    /**
     * @var TemplateFile
     */
    private $templateFile;

    public function __construct(Template $util, IO $io, TemplateFile $templateFile, array $inputValidators = [])
    {
        $this->util = $util;
        $this->templateFile = $templateFile;
        parent::__construct($io, $inputValidators);
    }

    public function configure()
    {
        $this
            ->setName('template:info')
            ->setDescription('Show extended info of specific template.')
            ->setHelp("This command displays a description of what the template does.")
            ->addArgument(
                Template::ARG_TEMPLATE,
                InputArgument::REQUIRED,
                'The template to show description for.'
            );
    }

    protected function interact(InputInterface $input, OutputInterface $output)
    {
        parent::interact($input, $output);
        $this->templateName = $this->util->getTemplateName();
    }

    protected function _execute(InputInterface $input, OutputInterface $output): void
    {
        $this->util->validateTemplate($this->templateName);
        $this->displayHeader();
        $this->displayDescription();
        $this->displayDependencies();
    }

    private function displayHeader(): void
    {
        $this->io->getInstance()->writeln('<comment>Template Info</comment>');
        $this->io->getInstance()->title($this->templateName);
    }

    private function displayDescription(): void
    {
        $description = $this->templateFile->getDescription($this->templateName);
        if ($description) {
            $this->io->getInstance()->text($description);
        } else {
            $this->io->getInstance()->text('Sorry, there is no info defined for this template.');
        }

    }

    private function displayDependencies(): void
    {
        $dependencies = $this->templateFile->getDependencies($this->templateName);
        if ($dependencies) {
            $this->io->getInstance()->note('DEPENDENCIES - This module will also load the following templates:');
            $this->io->getInstance()->text($dependencies);
        }
    }


}