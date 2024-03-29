<?php

/**
 * Copyright © 2023 Lingaro sp. z o.o. All rights reserved.
 * See LICENSE for license details.
 */

declare(strict_types=1);

namespace Lingaro\Magento2Codegen\Service\CommandUtil;

use Lingaro\Magento2Codegen\Service\CodeGenerator;
use Lingaro\Magento2Codegen\Service\FilepathUtil;
use Lingaro\Magento2Codegen\Service\IO;
use Lingaro\Magento2Codegen\Service\Magento\Detector;
use Lingaro\Magento2Codegen\Service\PropertyBagFactory;
use Lingaro\Magento2Codegen\Service\TemplateFactory;
use Lingaro\Magento2Codegen\Service\TemplateType\Module as ModuleTemplateType;
use Lingaro\Magento2Codegen\Util\PropertyBag;
use RuntimeException;

use function preg_match;

class Module
{
    private const MODULE_REGISTRATION_FILENAME = 'registration.php';

    private FilepathUtil $filepathUtil;
    private PropertyBagFactory $propertyBagFactory;
    private IO $io;
    private CodeGenerator $codeGenerator;
    private Template $templateCommandUtil;
    private Detector $detector;

    public function __construct(
        FilepathUtil $filepathUtil,
        PropertyBagFactory $propertyBagFactory,
        IO $io,
        CodeGenerator $codeGenerator,
        Template $templateCommandUtil,
        Detector $detector
    ) {
        $this->filepathUtil = $filepathUtil;
        $this->propertyBagFactory = $propertyBagFactory;
        $this->io = $io;
        $this->codeGenerator = $codeGenerator;
        $this->templateCommandUtil = $templateCommandUtil;
        $this->detector = $detector;
    }

    public function shouldCreateModule(): bool
    {
        if (!$this->detector->moduleExistsInDir($this->templateCommandUtil->getRootDir())) {
            $this->io->getInstance()->text('There is no module at the working directory.');
            if (
                !$this->io->getInstance()
                ->confirm('Would you like to create a new module now?', true)
            ) {
                throw new RuntimeException('Code generator needs to be executed in a valid module.');
            }
            return true;
        }
        return false;
    }

    public function createModule(TemplateFactory $templateFactory): void
    {
        $template = $templateFactory->create(ModuleTemplateType::MODULE_TEMPLATE_NAME);
        $this->codeGenerator->execute(
            $template,
            $this->templateCommandUtil->prepareProperties($template)
        );
    }

    public function getPropertyBag(): PropertyBag
    {
        $registrationAbsolutePath = $this->filepathUtil->getAbsolutePath(
            self::MODULE_REGISTRATION_FILENAME,
            $this->templateCommandUtil->getRootDir()
        );
        $content = $this->filepathUtil->getContent($registrationAbsolutePath);
        preg_match('/\'(.*)_(.*)\'/', $content, $matches);
        $propertyBag = $this->propertyBagFactory->create();
        if (isset($matches[1]) && isset($matches[2])) {
            $propertyBag['vendorName'] = $matches[1];
            $propertyBag['moduleName'] = $matches[2];
        }
        return $propertyBag;
    }
}
