<?php

namespace Orba\Magento2Codegen\Command\Template;

use Orba\Magento2Codegen\Command\AbstractCommand;
use Orba\Magento2Codegen\Service\CodeGenerator;
use Orba\Magento2Codegen\Service\CommandUtil\TemplateProperty;
use Orba\Magento2Codegen\Service\CommandUtil\TemplatePropertyFactory;
use Orba\Magento2Codegen\Service\IO;
use Orba\Magento2Codegen\Service\CommandUtil\Template;
use Orba\Magento2Codegen\Service\TemplateFile;
use Orba\Magento2Codegen\Service\TemplatePropertyBagFactory;
use Orba\Magento2Codegen\Util\TemplatePropertyBag;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class GenerateCommand extends AbstractCommand
{
    const OPTION_DRY_RUN = 'dry-run';
    const OPTION_ROOT_DIR = 'root-dir';

    /**
     * @var string|null
     */
    private $templateName;

    /**
     * @var Template
     */
    private $templateUtil;

    /**
     * @var TemplateFile
     */
    private $templateFile;

    /**
     * @var CodeGenerator
     */
    private $codeGenerator;

    /** @var TemplateProperty */
    private $templateProperty;

    public function __construct(
        Template $templateUtil,
        IO $io,
        TemplateFile $templateFile,
        CodeGenerator $codeGenerator,
        TemplateProperty $templateProperty,
        array $inputValidators = []
    )
    {
        $this->templateUtil = $templateUtil;
        $this->templateFile = $templateFile;
        $this->codeGenerator = $codeGenerator;
        $this->templateProperty = $templateProperty;
        parent::__construct($io, $inputValidators);
    }

    protected function configure()
    {
        $this
            ->setName('template:generate')
            ->setDescription('Generate code for desired template.')
            ->setHelp("This command generates code from a specific template.")
            ->addArgument(
                Template::ARG_TEMPLATE,
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

    /**
     * {@inheritdoc}
     */
    protected function interact(InputInterface $input, OutputInterface $output)
    {
        parent::interact($input, $output);
        $this->templateName = $this->templateUtil->getTemplateName();
    }

    protected function _execute(InputInterface $input, OutputInterface $output): void
    {
        $this->templateUtil->validateTemplate($this->templateName);
        $basePropertyBag = $this->getBasePropertyBag();
        $this->displayHeader();
        $this->displayTemplateDescription();
        $this->generate($basePropertyBag);
        $this->io->getInstance()->success('Success!');
    }

    private function displayHeader(): void
    {
        $this->io->getInstance()->writeln('<comment>Template Generate</comment>');
        $this->io->getInstance()->title($this->templateName);
    }

    private function displayTemplateDescription(): void
    {
        $description = $this->templateFile->getDescription($this->templateName);
        if ($description) {
            $this->io->getInstance()->block($description, 'Template description');
        }
    }

    private function getBasePropertyBag(): TemplatePropertyBag
    {
        if ($this->templateUtil->shouldCreateModule($this->templateName)) {
            return $this->generateModule();
        } else {
            return $this->templateUtil->getBasePropertyBag($this->templateName);
        }
    }

    private function generateModule(): TemplatePropertyBag
    {
        return $this->templateUtil->createModule(
            $this->templateProperty->withTemplateName(Template::TEMPLATE_MODULE)->prepareProperties()
        );
    }

    private function generate(TemplatePropertyBag $propertyBag): void
    {
        $this->codeGenerator->execute(
            $this->templateName,
            $this->templateProperty->withTemplateName($this->templateName)->prepareProperties($propertyBag)
        );
        $this->templateUtil->showInfoAfterGenerate($this->templateName, $propertyBag);
    }
}