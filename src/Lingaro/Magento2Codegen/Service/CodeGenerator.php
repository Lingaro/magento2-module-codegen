<?php

/**
 * Copyright © 2023 Lingaro sp. z o.o. All rights reserved.
 * See LICENSE for license details.
 */

declare(strict_types=1);

namespace Lingaro\Magento2Codegen\Service;

use Exception;
use Lingaro\Magento2Codegen\Command\Template\GenerateCommand;
use Lingaro\Magento2Codegen\Model\Template;
use Lingaro\Magento2Codegen\Util\PropertyBag;
use RuntimeException;
use Symfony\Component\Finder\SplFileInfo;

use function array_merge;
use function is_array;
use function preg_match;
use function sprintf;
use function str_replace;
use function trim;

class CodeGenerator
{
    private TemplateFile $templateFile;
    private TemplateProcessorInterface $templateProcessor;
    private CodeGeneratorUtil $codeGeneratorUtil;
    private FileMergerFactory $fileMergerFactory;
    private FilepathUtil $filepathUtil;
    private IO $io;

    public function __construct(
        TemplateFile $templateFile,
        TemplateProcessorInterface $templateProcessor,
        CodeGeneratorUtil $codeGeneratorUtil,
        FileMergerFactory $fileMergerFactory,
        FilepathUtil $filepathUtil,
        IO $io
    ) {
        $this->templateFile = $templateFile;
        $this->templateProcessor = $templateProcessor;
        $this->codeGeneratorUtil = $codeGeneratorUtil;
        $this->fileMergerFactory = $fileMergerFactory;
        $this->filepathUtil = $filepathUtil;
        $this->io = $io;
    }

    public function execute(Template $template, PropertyBag $propertyBag): void
    {
        $rootDir = $this->io->getInput()->getOption(GenerateCommand::OPTION_ROOT_DIR);
        $templateNames = [];
        foreach (array_merge([$template], $template->getDependencies()) as $item) {
            /** @var $item Template */
            $templateNames[] = $item->getName();
        }
        foreach ($this->templateFile->getTemplateFiles($templateNames) as $file) {
            if (preg_match('/\/_loop\(([^)]{1,})\):/', $file->getPathname(), $matches)) {
                $loopedPropertyName = $matches[1];
                $this->validateLoopedPropertyName($loopedPropertyName, $propertyBag, $file);
                foreach ($propertyBag[$loopedPropertyName] as $key => $item) {
                    $pathName = str_replace('_loop(' . $loopedPropertyName . '):', '', $file->getPathname());
                    $propertyBag->add(['_key' => $key, '_item' => $item]);
                    $this->processSingleFile($rootDir, $pathName, $file->getContents(), $propertyBag);
                }
                continue;
            }
            $this->processSingleFile($rootDir, $file->getPathname(), $file->getContents(), $propertyBag);
        }
    }

    private function processSingleFile(
        string $rootDir,
        string $pathName,
        string $content,
        PropertyBag $propertyBag
    ): void {
        $filePath = $this->codeGeneratorUtil->getDestinationFilePath(
            $this->templateProcessor->replacePropertiesInText($pathName, $propertyBag),
            $rootDir
        );
        $fileContent = $this->templateProcessor->replacePropertiesInText($content, $propertyBag);
        $this->generateFile($fileContent, $filePath);
    }

    private function generateFile(string $fileContent, string $filePath): void
    {
        $forceMerge = $this->io->getInput()->getOption(GenerateCommand::OPTION_FORCE_MERGE);

        if (empty(trim($fileContent))) {
            $this->io->getInstance()->note(sprintf('File omitted because of no content: %s', $filePath));
            return;
        }
        try {
            if (!$this->codeGeneratorUtil->canCopyWithoutOverriding($filePath)) {
                $merger = $this->fileMergerFactory->create($this->filepathUtil->getFilePath($filePath));
                $merged = false;
                /* condition for code merger confirmation */
                if (
                        $merger
                        && $this->codeGeneratorUtil->shouldMerge(
                            $filePath,
                            $merger->isExperimental(),
                            $forceMerge
                        )
                ) {
                    try {
                        $fileContent = $merger->merge($this->filepathUtil->getContent($filePath), $fileContent);
                        $merged = true;
                    } catch (Exception $e) {
                        $this->io->getInstance()->error($e->getMessage());
                    }
                }
                if (!$merged && !$this->codeGeneratorUtil->shouldOverride($filePath)) {
                    $this->io->getInstance()->note(sprintf('File omitted: %s', $filePath));
                    return;
                }
            }
            $this->codeGeneratorUtil->generateFileWithContent($filePath, $fileContent);
            $this->io->getInstance()->note(sprintf('File saved: %s', $filePath));
        } catch (Exception $e) {
            $this->io->getInstance()->error($e->getMessage());
        }
    }

    private function validateLoopedPropertyName(
        string $loopedPropertyName,
        PropertyBag $propertyBag,
        SplFileInfo $file
    ): void {
        if (!isset($propertyBag[$loopedPropertyName])) {
            throw new RuntimeException(sprintf(
                'Property "%s" is not defined. The following file cannot be processed: %s',
                $loopedPropertyName,
                $file->getPathname()
            ));
        }
        if (!is_array($propertyBag[$loopedPropertyName])) {
            throw new RuntimeException(sprintf(
                'Property "%s" is not an array. The following file cannot be processed: %s',
                $loopedPropertyName,
                $file->getPathname()
            ));
        }
    }
}
