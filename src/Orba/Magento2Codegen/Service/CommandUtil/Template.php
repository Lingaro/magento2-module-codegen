<?php

namespace Orba\Magento2Codegen\Service\CommandUtil;

use Exception;
use InvalidArgumentException;
use Orba\Magento2Codegen\Command\Template\GenerateCommand;
use Orba\Magento2Codegen\Helper\IO;
use Orba\Magento2Codegen\Service\CodeGenerator;
use Orba\Magento2Codegen\Service\TemplateFile;
use Orba\Magento2Codegen\Service\TemplatePropertyBagFactory;
use Orba\Magento2Codegen\Util\TemplatePropertyBag;

class Template
{
    const ARG_TEMPLATE = 'template';

    const TEMPLATE_MODULE = 'module';
    const TEMPLATE_LANGUAGE = 'language';

    /**
     * @var array
     */
    private $moduleNoRequiredTemplates = [
        self::TEMPLATE_MODULE,
        self::TEMPLATE_LANGUAGE,
    ];

    /**
     * @var TemplateFile
     */
    private $templateFile;

    /**
     * @var TemplateProperty
     */
    private $propertyUtil;

    /**
     * @var TemplatePropertyBagFactory
     */
    private $propertyBagFactory;

    /**
     * @var Module
     */
    private $module;

    /**
     * @var CodeGenerator
     */
    private $codeGenerator;

    public function __construct(
        TemplateFile $templateFile,
        TemplateProperty $propertyUtil,
        TemplatePropertyBagFactory $propertyBagFactory,
        Module $module,
        CodeGenerator $codeGenerator
    )
    {
        $this->templateFile = $templateFile;
        $this->propertyUtil = $propertyUtil;
        $this->propertyBagFactory = $propertyBagFactory;
        $this->module = $module;
        $this->codeGenerator = $codeGenerator;
    }

    public function getTemplateName(IO $io): string
    {
        return $io->getInput()->getArgument(self::ARG_TEMPLATE)
            ?: $io->ask('Please specify a template:', '');
    }

    /**
     * @param string|null $templateName
     * @return bool
     * @throws InvalidArgumentException
     */
    public function validateTemplate(?string $templateName): bool
    {
        if (!$templateName) {
            throw new InvalidArgumentException('Template name cannot be empty.');
        }
        if (!$this->templateFile->exists($templateName)) {
            throw new InvalidArgumentException(sprintf('Template "%s" does not exists.', $templateName));
        }
        return true;
    }

    public function prepareProperties(
        string $templateFile,
        IO $io,
        ?TemplatePropertyBag $basePropertyBag = null
    ): TemplatePropertyBag
    {
        $propertyBag = $basePropertyBag ?: $this->propertyBagFactory->create();
        $this->propertyUtil->setDefaultProperties($propertyBag);
        //$this->beforeAskInputProperties($propertyBag);
        $this->propertyUtil->askAndSetInputPropertiesForTemplate($propertyBag, $templateFile, $io);
        return $propertyBag;
    }

    public function shouldCreateModule(string $templateName, IO $io): bool
    {
        if (!$this->module->exists($this->getRootDir($io))
            && !in_array($templateName, $this->moduleNoRequiredTemplates)) {
            $io->text('There is no module at the working directory.');
            if (!$io->confirm('Would you like to create a new module now?', true)) {
                throw new Exception('Code generator needs to be executed in a valid module.');
            }
            return true;
        }
        return false;
    }

    public function createModule(TemplatePropertyBag $propertyBag, IO $io): TemplatePropertyBag
    {
        $this->codeGenerator->execute(
            Template::TEMPLATE_MODULE,
            $propertyBag,
            $io
        );
        return $propertyBag;
    }

    public function getBasePropertyBag(string $templateName, IO $io): TemplatePropertyBag
    {
        if (in_array($templateName, $this->moduleNoRequiredTemplates)) {
            return $this->propertyBagFactory->create();
        } else {
            return $this->module->getPropertyBag($this->getRootDir($io));
        }
    }

    public function showInfoAfterGenerate(string $templateName, TemplatePropertyBag $propertyBag, IO $io): void
    {
        $manualSteps = $this->templateFile->getManualSteps($templateName, $propertyBag);
        if ($manualSteps) {
            $io->note('This template needs you to take care of the following manual steps:');
            $io->text($manualSteps);
        }
    }

    private function getRootDir(IO $io):? string
    {
        return $io->getInput()->getOption(GenerateCommand::OPTION_ROOT_DIR) ?: null;
    }
}