<?php

namespace Orba\Magento2Codegen\Service;

use Exception;
use Orba\Magento2Codegen\Command\Template\GenerateCommand;
use Orba\Magento2Codegen\Helper\IO;
use Orba\Magento2Codegen\Util\TemplatePropertyBag;

class CodeGenerator
{
    /**
     * @var TemplateFile
     */
    private $templateFile;

    /**
     * @var TemplatePropertyUtil
     */
    private $propertyUtil;

    /**
     * @var CodeGeneratorUtil
     */
    private $codeGeneratorUtil;

    /**
     * @var FileMergerFactory
     */
    private $fileMergerFactory;

    /**
     * @var FilepathUtil
     */
    private $filepathUtil;

    public function __construct(
        TemplateFile $templateFile,
        TemplatePropertyUtil $propertyUtil,
        CodeGeneratorUtil $codeGeneratorUtil,
        FileMergerFactory $fileMergerFactory,
        FilepathUtil $filepathUtil
    )
    {
        $this->templateFile = $templateFile;
        $this->propertyUtil = $propertyUtil;
        $this->codeGeneratorUtil = $codeGeneratorUtil;
        $this->fileMergerFactory = $fileMergerFactory;
        $this->filepathUtil = $filepathUtil;
    }

    public function execute(string $templateName, TemplatePropertyBag $propertyBag, IO $io): void
    {
        $dryRun = $io->getInput()->getOption(GenerateCommand::OPTION_DRY_RUN);
        $rootDir = $io->getInput()->getOption(GenerateCommand::OPTION_ROOT_DIR) ?: null;
        foreach ($this->templateFile->getTemplateFiles([$templateName]) as $file) {
            $filePath = $this->codeGeneratorUtil->getDestinationFilePath(
                $this->propertyUtil->replacePropertiesInText($file->getPathname(), $propertyBag),
                $rootDir
            );
            $fileContent = $this->propertyUtil->replacePropertiesInText($file->getContents(), $propertyBag);
            if (!$dryRun) {
                try {
                    if (!$this->codeGeneratorUtil->canCopyWithoutOverriding($filePath)) {
                        $merger = $this->fileMergerFactory->create($this->filepathUtil->getFileName($filePath));
                        $merged = false;
                        if ($merger && $this->codeGeneratorUtil->shouldMerge($filePath, $io)) {
                            $fileContent = $merger->merge($this->filepathUtil->getContent($filePath), $fileContent);
                            $merged = true;
                        }
                        if (!$merged && !$this->codeGeneratorUtil->shouldOverride($filePath, $io)) {
                            $io->note(sprintf('File omitted: %s', $filePath));
                            continue;
                        }
                    }
                    $this->codeGeneratorUtil->generateFileWithContent($filePath, $fileContent);
                    $io->note(sprintf('File saved: %s', $filePath));
                } catch (Exception $e) {
                    $io->error($e->getMessage());
                }
            }
        }
    }
}