<?php

/**
 * @copyright Copyright © 2021 Orba. All rights reserved.
 * @author    info@orba.co
 */

declare(strict_types=1);

namespace Orba\Magento2Codegen\Service\CommandUtil;

use Orba\Magento2Codegen\Command\Template\GenerateCommand;
use Orba\Magento2Codegen\Model\InputPropertyInterface;
use Orba\Magento2Codegen\Model\Template as TemplateModel;
use Orba\Magento2Codegen\Service\IO;
use Orba\Magento2Codegen\Service\PropertyDependencyChecker;
use Orba\Magento2Codegen\Service\PropertyValueCollector\CollectorFactory;
use Orba\Magento2Codegen\Service\PropertyBagFactory;
use Orba\Magento2Codegen\Service\TemplateProcessorInterface;
use Orba\Magento2Codegen\Util\PropertyBag;

use function array_merge;

class Template
{
    public const ARG_TEMPLATE = 'template';

    private PropertyBagFactory $propertyBagFactory;
    private IO $io;
    private TemplateProperty $templatePropertyUtil;
    private CollectorFactory $propertyValueCollectorFactory;
    private TemplateProcessorInterface $templateProcessor;
    private PropertyDependencyChecker $propertyDependencyChecker;
    private PropertyCollector $propertyCollectorUtil;

    public function __construct(
        PropertyBagFactory $propertyBagFactory,
        IO $io,
        TemplateProperty $templatePropertyUtil,
        CollectorFactory $propertyValueCollectorFactory,
        TemplateProcessorInterface $templateProcessor,
        PropertyDependencyChecker $propertyDependencyChecker,
        PropertyCollector $propertyCollectorUtil
    ) {
        $this->propertyBagFactory = $propertyBagFactory;
        $this->io = $io;
        $this->templatePropertyUtil = $templatePropertyUtil;
        $this->propertyValueCollectorFactory = $propertyValueCollectorFactory;
        $this->templateProcessor = $templateProcessor;
        $this->propertyDependencyChecker = $propertyDependencyChecker;
        $this->propertyCollectorUtil = $propertyCollectorUtil;
    }

    public function getTemplateName(): string
    {
        $template = $this->io->getInput()->getArgument(self::ARG_TEMPLATE);
        if (!$template) {
            $template = $this->io->getInstance()->ask('Please specify a template:', '');
            $this->io->getInput()->setArgument(self::ARG_TEMPLATE, $template);
        }
        return $template;
    }

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
            if (
                $property instanceof InputPropertyInterface
                && !$this->propertyDependencyChecker->areRootConditionsMet($property, $propertyBag)
            ) {
                continue;
            }
            $valueCollector = $this->propertyValueCollectorFactory->create(
                $this->propertyCollectorUtil->getType(),
                $property
            );
            $propertyBag[$property->getName()] = $valueCollector->collectValue($property, $propertyBag);
        }
        return $propertyBag;
    }

    public function getRootDir(): string
    {
        return $this->io->getInput()->getOption(GenerateCommand::OPTION_ROOT_DIR);
    }
}
