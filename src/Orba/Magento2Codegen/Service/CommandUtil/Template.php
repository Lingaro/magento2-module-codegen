<?php

namespace Orba\Magento2Codegen\Service\CommandUtil;

use Orba\Magento2Codegen\Command\Template\GenerateCommand;
use Orba\Magento2Codegen\Model\PropertyInterface;
use Orba\Magento2Codegen\Model\Template as TemplateModel;
use Orba\Magento2Codegen\Service\CodeGenerator;
use Orba\Magento2Codegen\Service\IO;
use Orba\Magento2Codegen\Service\PropertyValueCollector\CollectorFactory;
use Orba\Magento2Codegen\Service\PropertyBagFactory;
use Orba\Magento2Codegen\Service\TemplateProcessorInterface;
use Orba\Magento2Codegen\Util\PropertyBag;

/**
 * Class Template
 * @package Orba\Magento2Codegen\Service\CommandUtil
 */
class Template
{
    public const ARG_TEMPLATE = 'template';

    /**
     * @var PropertyBagFactory
     */
    private $propertyBagFactory;

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

    /**
     * @var TemplateProcessorInterface
     */
    private $templateProcessor;

    public function __construct(
        PropertyBagFactory $propertyBagFactory,
        CodeGenerator $codeGenerator,
        IO $io,
        TemplateProperty $templatePropertyUtil,
        CollectorFactory $propertyValueCollectorFactory,
        TemplateProcessorInterface $templateProcessor
    )
    {
        $this->propertyBagFactory = $propertyBagFactory;
        $this->codeGenerator = $codeGenerator;
        $this->io = $io;
        $this->templatePropertyUtil = $templatePropertyUtil;
        $this->propertyValueCollectorFactory = $propertyValueCollectorFactory;
        $this->templateProcessor = $templateProcessor;
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
     * @param TemplateModel $template
     * @param PropertyBag $propertyBag
     */
    public function showInfoAfterGenerate(TemplateModel $template, PropertyBag $propertyBag): void
    {
        if ($template->getAfterGenerateConfig()) {
            $afterGenerate = $this->templateProcessor
                ->replacePropertiesInText($template->getAfterGenerateConfig(), $propertyBag);
            if ($afterGenerate) {
                $this->io->getInstance()->note('Post-generation information:');
                $this->io->getInstance()->text($afterGenerate);
            }
        }
    }

    public function prepareProperties(TemplateModel $template, ?PropertyBag $basePropertyBag = null): PropertyBag
    {
        $propertyBag = $basePropertyBag ?: $this->propertyBagFactory->create();
        $properties = array_merge(
            $this->templatePropertyUtil->collectConstProperties(),
            $this->templatePropertyUtil->collectInputProperties($template)
        );
        foreach ($properties as $property) {
            /** @var PropertyInterface $property */
            $valueCollector = $this->propertyValueCollectorFactory->create($property);
            $propertyBag[$property->getName()] = $valueCollector->collectValue($property);
        }
        return $propertyBag;
    }

    public function getRootDir(): string
    {
        return $this->io->getInput()->getOption(GenerateCommand::OPTION_ROOT_DIR);
    }
}
