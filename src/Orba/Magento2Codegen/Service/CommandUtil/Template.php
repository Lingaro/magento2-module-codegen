<?php

namespace Orba\Magento2Codegen\Service\CommandUtil;

use Exception;
use InvalidArgumentException;
use Orba\Magento2Codegen\Command\Template\GenerateCommand;
use Orba\Magento2Codegen\Service\CodeGenerator;
use Orba\Magento2Codegen\Service\IO;
use Orba\Magento2Codegen\Service\TemplateFile;
use Orba\Magento2Codegen\Service\TemplatePropertyBagFactory;
use Orba\Magento2Codegen\Util\TemplatePropertyBag;

/**
 * Class Template
 * @package Orba\Magento2Codegen\Service\CommandUtil
 */
class Template
{
    public const ARG_TEMPLATE = 'template';
    public const TEMPLATE_MODULE = 'module';
    public const TEMPLATE_LANGUAGE = 'language';

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

    /**
     * @var IO
     */
    private $io;

    /**
     * Template constructor.
     * @param TemplateFile $templateFile
     * @param TemplatePropertyBagFactory $propertyBagFactory
     * @param Module $module
     * @param CodeGenerator $codeGenerator
     * @param IO $io
     */
    public function __construct(
        TemplateFile $templateFile,
        TemplatePropertyBagFactory $propertyBagFactory,
        Module $module,
        CodeGenerator $codeGenerator,
        IO $io
    )
    {
        $this->templateFile = $templateFile;
        $this->propertyBagFactory = $propertyBagFactory;
        $this->module = $module;
        $this->codeGenerator = $codeGenerator;
        $this->io = $io;
    }

    /**
     * @return string
     */
    public function getTemplateName(): string
    {
        $template = $this->io->getInput()->getArgument(self::ARG_TEMPLATE);
        if (!$template) {
            $template = $this->io->getInstance()->ask('Please specify a template:', '');
            $this->io->getInput()->setArgument(self::ARG_TEMPLATE, $template);
        }
        return $template;
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

    /**
     * @param string $templateName
     * @return bool
     * @throws Exception
     */
    public function shouldCreateModule(string $templateName): bool
    {
        if (!$this->module->exists($this->getRootDir())
            && !in_array($templateName, $this->moduleNoRequiredTemplates)) {
            $this->io->getInstance()->text('There is no module at the working directory.');
            if (!$this->io->getInstance()
                ->confirm('Would you like to create a new module now?', true)) {
                throw new Exception('Code generator needs to be executed in a valid module.');
            }
            return true;
        }
        return false;
    }

    /**
     * @param TemplatePropertyBag $propertyBag
     * @return TemplatePropertyBag
     */
    public function createModule(TemplatePropertyBag $propertyBag): TemplatePropertyBag
    {
        $this->codeGenerator->execute(Template::TEMPLATE_MODULE, $propertyBag);
        return $propertyBag;
    }

    /**
     * @param string $templateName
     * @return TemplatePropertyBag
     */
    public function getBasePropertyBag(string $templateName): TemplatePropertyBag
    {
        if (in_array($templateName, $this->moduleNoRequiredTemplates)) {
            return $this->propertyBagFactory->create();
        } else {
            return $this->module->getPropertyBag($this->getRootDir());
        }
    }

    /**
     * @param string $templateName
     * @param TemplatePropertyBag $propertyBag
     */
    public function showInfoAfterGenerate(string $templateName, TemplatePropertyBag $propertyBag): void
    {
        $manualSteps = $this->templateFile->getManualSteps($templateName, $propertyBag);
        if ($manualSteps) {
            $this->io->getInstance()->note('This template needs you to take care of the following manual steps:');
            $this->io->getInstance()->text($manualSteps);
        }
    }

    /**
     * @return string
     */
    private function getRootDir(): string
    {
        return $this->io->getInput()->getOption(GenerateCommand::OPTION_ROOT_DIR);
    }
}
