<?php

/**
 * @copyright Copyright © 2021 Orba. All rights reserved.
 * @author    info@orba.co
 */

declare(strict_types=1);

namespace Orba\Magento2Codegen\Command\Template;

use Orba\Magento2Codegen\Command\AbstractCommand;
use Orba\Magento2Codegen\Model\Template;
use Orba\Magento2Codegen\Service\CodeGenerator;
use Orba\Magento2Codegen\Service\IO;
use Orba\Magento2Codegen\Service\CommandUtil\Template as TemplateCommandUtil;
use Orba\Magento2Codegen\Service\TemplateFactory;
use Orba\Magento2Codegen\Util\PropertyBag;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class GenerateCommand extends AbstractCommand
{
    public const OPTION_DRY_RUN = 'dry-run';
    public const OPTION_ROOT_DIR = 'root-dir';

    private ?string $templateName;
    private TemplateCommandUtil $templateUtil;
    private CodeGenerator $codeGenerator;
    private TemplateFactory $templateFactory;

    public function __construct(
        TemplateCommandUtil $templateUtil,
        IO $io,
        CodeGenerator $codeGenerator,
        TemplateFactory $templateFactory,
        array $inputValidators = []
    ) {
        $this->templateUtil = $templateUtil;
        $this->codeGenerator = $codeGenerator;
        $this->templateFactory = $templateFactory;
        parent::__construct($io, $inputValidators);
    }

    protected function configure(): void
    {
        $this
            ->setName('template:generate')
            ->setDescription('Generate code for desired template.')
            ->setHelp("This command generates code from a specific template.")
            ->addArgument(
                TemplateCommandUtil::ARG_TEMPLATE,
                InputArgument::REQUIRED,
                'The template used to generate the code.'
            )->addOption(
                self::OPTION_ROOT_DIR,
                null,
                InputOption::VALUE_REQUIRED,
                'If specified, code is generated on this root directory.',
                getcwd()
            )->addOption(
                self::OPTION_DRY_RUN,
                null,
                InputOption::VALUE_NONE,
                'If specified, no files will be generated.'
            );
    }

    protected function interact(InputInterface $input, OutputInterface $output): void
    {
        parent::interact($input, $output);
        $this->templateName = $this->templateUtil->getTemplateName();
    }

    protected function executeInternal(InputInterface $input, OutputInterface $output): void
    {
        $template = $this->templateFactory->create($this->templateName);
        if ($template->getIsAbstract()) {
            $this->io->getInstance()->warning('Can\'t generate an abstract template!');
            return;
        }
        if ($template->getTypeService()->beforeGenerationCommand($template) === false) {
            $this->io->getInstance()->warning('Execution stopped!');
            return;
        }
        $basePropertyBag = $template->getTypeService()->getBasePropertyBag();
        $this->displayHeader($template);
        $this->displayTemplateDescription($template);
        $this->generate($template, $basePropertyBag);
        $this->io->getInstance()->success('Success!');
    }

    private function displayHeader(Template $template): void
    {
        $this->io->getInstance()->writeln('<comment>Template Generate</comment>');
        $this->io->getInstance()->title($template->getName());
    }

    private function displayTemplateDescription(Template $template): void
    {
        $description = $template->getDescription();
        if ($description) {
            $this->io->getInstance()->text(explode("\n", $description));
        }
    }

    private function generate(Template $template, PropertyBag $propertyBag): void
    {
        $this->codeGenerator->execute(
            $template,
            $this->templateUtil->prepareProperties($template, $propertyBag)
        );
        $this->templateUtil->showInfoAfterGenerate($template, $propertyBag);
    }
}
