<?php

namespace Orba\Magento2Codegen\Service\CommandUtil;

use Exception;
use InvalidArgumentException;
use Orba\Magento2Codegen\Command\Template\GenerateCommand;
use Orba\Magento2Codegen\Model\PropertyInterface;
use Orba\Magento2Codegen\Service\CodeGenerator;
use Orba\Magento2Codegen\Service\IO;
use Orba\Magento2Codegen\Service\PropertyValueCollector\CollectorFactory;
use Orba\Magento2Codegen\Service\TemplateFile;
use Orba\Magento2Codegen\Service\PropertyBagFactory;
use Orba\Magento2Codegen\Util\PropertyBag;
use phpDocumentor\Reflection\Types\Boolean;

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
     * @var PropertyBagFactory
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
     * @var TemplateProperty
     */
    private $templatePropertyUtil;

    /**
     * @var CollectorFactory
     */
    private $propertyValueCollectorFactory;

    public function __construct(
        TemplateFile $templateFile,
        PropertyBagFactory $propertyBagFactory,
        Module $module,
        CodeGenerator $codeGenerator,
        IO $io,
        TemplateProperty $templatePropertyUtil,
        CollectorFactory $propertyValueCollectorFactory
    ) {
        $this->templateFile = $templateFile;
        $this->propertyBagFactory = $propertyBagFactory;
        $this->module = $module;
        $this->codeGenerator = $codeGenerator;
        $this->io = $io;
        $this->templatePropertyUtil = $templatePropertyUtil;
        $this->propertyValueCollectorFactory = $propertyValueCollectorFactory;
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
     * @throws InvalidArgumentException
     */
    public function checkTemplate(string $templateName): bool
    {
        if ($this->templateFile->getIsAbstract($templateName)) {
            throw new InvalidArgumentException('Template "%s" is abstract.',$templateName);
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
     * @param PropertyBag $propertyBag
     * @return PropertyBag
     */
    public function createModule(PropertyBag $propertyBag): PropertyBag
    {
        $this->codeGenerator->execute(Template::TEMPLATE_MODULE, $propertyBag);
        return $propertyBag;
    }

    /**
     * @param string $templateName
     * @return PropertyBag
     */
    public function getBasePropertyBag(string $templateName): PropertyBag
    {
        if (in_array($templateName, $this->moduleNoRequiredTemplates)) {
            return $this->propertyBagFactory->create();
        } else {
            return $this->module->getPropertyBag($this->getRootDir());
        }
    }

    /**
     * @param string $templateName
     * @param PropertyBag $propertyBag
     */
    public function showInfoAfterGenerate(string $templateName, PropertyBag $propertyBag): void
    {
        $afterGenerate = $this->templateFile->getAfterGenerate($templateName, $propertyBag);
        if ($afterGenerate) {
            $this->io->getInstance()->note('Post-generation information:');
            $this->io->getInstance()->text($afterGenerate);
        }
    }

    public function prepareProperties(string $templateName, ?PropertyBag $basePropertyBag = null): PropertyBag
    {
        $propertyBag = $basePropertyBag ?: $this->propertyBagFactory->create();
        $properties = array_merge(
            $this->templatePropertyUtil->collectConstProperties(),
            $this->templatePropertyUtil->collectInputProperties($templateName)
        );
        foreach ($properties as $property) {
            /** @var PropertyInterface $property */
            $valueCollector = $this->propertyValueCollectorFactory->create($property);
            $propertyBag[$property->getName()] = $valueCollector->collectValue($property);
        }
        return $propertyBag;
    }

    /**
     * @return string
     */
    private function getRootDir(): string
    {
        return $this->io->getInput()->getOption(GenerateCommand::OPTION_ROOT_DIR);
    }
}
