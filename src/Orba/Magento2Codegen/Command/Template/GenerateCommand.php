<?php

/**
 * @copyright Copyright © 2022 Orba. All rights reserved.
 * @author    info@orba.co
 */

declare(strict_types=1);

namespace Orba\Magento2Codegen\Command\Template;

use InvalidArgumentException;
use Orba\Magento2Codegen\Command\AbstractCommand;
use Orba\Magento2Codegen\Model\Template;
use Orba\Magento2Codegen\Service\CodeGenerator;
use Orba\Magento2Codegen\Service\CommandUtil\PropertyCollector as PropertyCollectorCommandUtil;
use Orba\Magento2Codegen\Service\CommandUtil\Template as TemplateCommandUtil;
use Orba\Magento2Codegen\Service\IO;
use Orba\Magento2Codegen\Service\TemplateFactory;
use Orba\Magento2Codegen\Util\PropertyBag;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

use function sprintf;

class GenerateCommand extends AbstractCommand
{
    public const OPTION_ROOT_DIR = 'root-dir';
    public const OPTION_ROOT_DIR_SHORT = 'r';
    public const OPTION_FORCE_MERGE = 'force-merge';
    public const OPTION_FORCE_MERGE_SHORT = 'm';
    public const OPTION_FORCE_OVERRIDE = 'force-override';
    public const OPTION_FORCE_OVERRIDE_SHORT = 'o';
    public const OPTION_YAML_PATH = 'yaml-path';
    public const OPTION_YAML_PATH_SHORT = 'y';
    public const FORCE_MERGE_ALL = 'all';
    public const FORCE_MERGE_NONEXPERIMENTAL = 'nonexperimental';

    private ?string $templateName;
    private TemplateCommandUtil $templateUtil;
    private CodeGenerator $codeGenerator;
    private TemplateFactory $templateFactory;
    private PropertyCollectorCommandUtil $propertyCollectorUtil;

    public function __construct(
        TemplateCommandUtil $templateUtil,
        IO $io,
        CodeGenerator $codeGenerator,
        TemplateFactory $templateFactory,
        PropertyCollectorCommandUtil $optionUtil,
        array $inputValidators = []
    ) {
        $this->templateUtil = $templateUtil;
        $this->codeGenerator = $codeGenerator;
        $this->templateFactory = $templateFactory;
        $this->propertyCollectorUtil = $optionUtil;
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
                self::OPTION_ROOT_DIR_SHORT,
                InputOption::VALUE_REQUIRED,
                'If specified, code is generated on this root directory.',
                getcwd()
            )->addOption(
                self::OPTION_FORCE_MERGE,
                self::OPTION_FORCE_MERGE_SHORT,
                InputOption::VALUE_REQUIRED,
                sprintf(
                    'Use "%s" to automatically run all code mergers. '
                    . 'Use "%s" to automatically run non-experimental code mergers.',
                    self::FORCE_MERGE_ALL,
                    self::FORCE_MERGE_NONEXPERIMENTAL
                )
            )->addOption(
                self::OPTION_FORCE_OVERRIDE,
                self::OPTION_FORCE_OVERRIDE_SHORT,
                InputOption::VALUE_NONE,
                'If specified, all unmerged files will be automatically overridden.'
            )->addOption(
                self::OPTION_YAML_PATH,
                self::OPTION_YAML_PATH_SHORT,
                InputOption::VALUE_REQUIRED,
                'If specified, property values will be collected from YAML file instead of console prompts.'
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
        try {
            $this->propertyCollectorUtil->handleAdditionalLogic();
        } catch (InvalidArgumentException $e) {
            $this->io->getInstance()->warning($e->getMessage());
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
