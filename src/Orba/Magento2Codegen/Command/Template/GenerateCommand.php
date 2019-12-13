<?php

namespace Orba\Magento2Codegen\Command\Template;

use Exception;
use Orba\Magento2Codegen\Helper\IO;
use Orba\Magento2Codegen\Service\CodeGenerator;
use Orba\Magento2Codegen\Service\IOFactory;
use Orba\Magento2Codegen\Service\CommandUtil\Template;
use Orba\Magento2Codegen\Service\TemplateFile;
use Orba\Magento2Codegen\Service\TemplatePropertyBagFactory;
use Orba\Magento2Codegen\Util\TemplatePropertyBag;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class GenerateCommand extends Command
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
    private $util;

    /**
     * @var IOFactory
     */
    private $ioFactory;

    /**
     * @var IO
     */
    private $io;

    /**
     * @var TemplateFile
     */
    private $templateFile;

    /**
     * @var CodeGenerator
     */
    private $codeGenerator;

    /**
     * @var TemplatePropertyBagFactory
     */
    private $propertyBagFactory;

    public function __construct(
        Template $util,
        IOFactory $ioFactory,
        TemplateFile $templateFile,
        CodeGenerator $codeGenerator,
        TemplatePropertyBagFactory $propertyBagFactory
    )
    {
        parent::__construct();
        $this->util = $util;
        $this->ioFactory = $ioFactory;
        $this->templateFile = $templateFile;
        $this->codeGenerator = $codeGenerator;
        $this->propertyBagFactory = $propertyBagFactory;
    }

    public function configure()
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
                'If specified, code is generated on this root directory.'
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
        $this->io = $this->ioFactory->create($input, $output);
        $this->templateName = $this->util->getTemplateName($this->io);
    }

    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        try {
            $this->util->validateTemplate($this->templateName);
            $basePropertyBag = $this->getBasePropertyBag();
            $this->displayHeader();
            $this->generate($basePropertyBag);
            $this->io->success('Success!');
        } catch (Exception $e) {
            $this->io->error($e->getMessage());
        }
    }

    private function displayHeader(): void
    {
        $this->io->writeln('<comment>Template Generate</comment>');
        $this->io->title($this->templateName);
    }

    private function getBasePropertyBag(): TemplatePropertyBag
    {
        if ($this->util->shouldCreateModule($this->templateName, $this->io)) {
            return $this->generateModule();
        } else {
            return $this->util->getBasePropertyBag($this->templateName, $this->io);
        }
    }

    private function generateModule(): TemplatePropertyBag
    {
        return $this->util->createModule(
            $this->util->prepareProperties(Template::TEMPLATE_MODULE, $this->io),
            $this->io
        );
    }

    private function generate(TemplatePropertyBag $propertyBag): void
    {
        $this->codeGenerator->execute(
            $this->templateName,
            $this->util->prepareProperties($this->templateName, $this->io, $propertyBag),
            $this->io
        );
        $this->util->showInfoAfterGenerate($this->templateName, $propertyBag, $this->io);
    }
}