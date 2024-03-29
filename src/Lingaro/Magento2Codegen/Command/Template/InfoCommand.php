<?php

/**
 * Copyright © 2023 Lingaro sp. z o.o. All rights reserved.
 * See LICENSE for license details.
 */

declare(strict_types=1);

namespace Lingaro\Magento2Codegen\Command\Template;

use Lingaro\Magento2Codegen\Command\AbstractCommand;
use Lingaro\Magento2Codegen\Model\Template;
use Lingaro\Magento2Codegen\Service\IO;
use Lingaro\Magento2Codegen\Service\CommandUtil\Template as TemplateCommandUtil;
use Lingaro\Magento2Codegen\Service\TemplateFactory;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class InfoCommand extends AbstractCommand
{
    private ?string $templateName;
    private TemplateCommandUtil $util;
    private TemplateFactory $templateFactory;

    public function __construct(
        TemplateCommandUtil $util,
        IO $io,
        TemplateFactory $templateFactory,
        array $inputValidators = []
    ) {
        $this->util = $util;
        $this->templateFactory = $templateFactory;
        parent::__construct($io, $inputValidators);
    }

    public function configure(): void
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

    protected function interact(InputInterface $input, OutputInterface $output): void
    {
        parent::interact($input, $output);
        $this->templateName = $this->util->getTemplateName();
    }

    protected function executeInternal(InputInterface $input, OutputInterface $output): void
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
        $this->io->getInstance()->text(
            $template->getDescription() ?: 'Sorry, there is no info defined for this template.'
        );
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
