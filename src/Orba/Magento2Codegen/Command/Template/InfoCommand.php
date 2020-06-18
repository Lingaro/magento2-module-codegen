<?php

namespace Orba\Magento2Codegen\Command\Template;

use Orba\Magento2Codegen\Command\AbstractCommand;
use Orba\Magento2Codegen\Model\Template;
use Orba\Magento2Codegen\Service\IO;
use Orba\Magento2Codegen\Service\CommandUtil\Template as TemplateCommandUtil;
use Orba\Magento2Codegen\Service\TemplateFactory;
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
     * @var TemplateCommandUtil
     */
    private $util;

    /**
     * @var TemplateFactory
     */
    private $templateFactory;

    public function __construct(
        TemplateCommandUtil $util,
        IO $io,
        TemplateFactory $templateFactory,
        array $inputValidators = []
    )
    {
        $this->util = $util;
        $this->templateFactory = $templateFactory;
        parent::__construct($io, $inputValidators);
    }

    public function configure()
    {
        $this
            ->setName('template:info')
            ->setDescription('Show extended info of specific template.')
            ->setHelp("This command displays a description of what the template does.")
            ->addArgument(
                TemplateCommandUtil::ARG_TEMPLATE,
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
        $template = $this->templateFactory->create($this->templateName);
        $this->displayHeader($template);
        $this->displayDescription($template);
        $this->displayDependencies($template);
    }

    private function displayHeader(Template $template): void
    {
        $this->io->getInstance()->writeln('<comment>Template Info</comment>');
        $this->io->getInstance()->title($template->getName());
    }

    private function displayDescription(Template $template): void
    {
        $description = $template->getDescription();
        if ($description) {
            $this->io->getInstance()->text($description);
        } else {
            $this->io->getInstance()->text('Sorry, there is no info defined for this template.');
        }
    }

    private function displayDependencies(Template $template): void
    {
        $dependencies = $template->getDependencies();
        if (!empty($dependencies)) {
            $this->io->getInstance()->note('DEPENDENCIES - This module will also load the following templates:');
            foreach ($dependencies as $dependency) {
                $this->io->getInstance()->text($dependency->getName());
            }
        }
    }
}
