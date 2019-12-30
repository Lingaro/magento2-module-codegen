<?php

namespace Orba\Magento2Codegen\Service;

use Exception;
use Orba\Magento2Codegen\Command\Template\GenerateCommand;
use Orba\Magento2Codegen\Util\TemplatePropertyBag;

class CodeGenerator
{
    /**
     * @var TemplateFile
     */
    private $templateFile;

    /**
     * @var TemplateProcessorInterface
     */
    private $templateProcessor;

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

    /**
     * @var IO
     */
    private $io;

    public function __construct(
        TemplateFile $templateFile,
        TemplateProcessorInterface $templateProcessor,
        CodeGeneratorUtil $codeGeneratorUtil,
        FileMergerFactory $fileMergerFactory,
        FilepathUtil $filepathUtil,
        IO $io
    )
    {
        $this->templateFile = $templateFile;
        $this->templateProcessor = $templateProcessor;
        $this->codeGeneratorUtil = $codeGeneratorUtil;
        $this->fileMergerFactory = $fileMergerFactory;
        $this->filepathUtil = $filepathUtil;
        $this->io = $io;
    }

    public function execute(string $templateName, TemplatePropertyBag $propertyBag): void
    {
        $dryRun = $this->io->getInput()->getOption(GenerateCommand::OPTION_DRY_RUN);
        $rootDir = $this->io->getInput()->getOption(GenerateCommand::OPTION_ROOT_DIR);
        foreach ($this->templateFile->getTemplateFiles([$templateName]) as $file) {
            $filePath = $this->codeGeneratorUtil->getDestinationFilePath(
                $this->templateProcessor->replacePropertiesInText($file->getPathname(), $propertyBag),
                $rootDir
            );
            $fileContent = $this->templateProcessor->replacePropertiesInText($file->getContents(), $propertyBag);
            if (!$dryRun) {
                try {
                    if (!$this->codeGeneratorUtil->canCopyWithoutOverriding($filePath)) {
                        $merger = $this->fileMergerFactory->create($this->filepathUtil->getFileName($filePath));
                        $merged = false;
                        if ($merger && $this->codeGeneratorUtil->shouldMerge($filePath)) {
                            $fileContent = $merger->merge($this->filepathUtil->getContent($filePath), $fileContent);
                            $merged = true;
                        }
                        if (!$merged && !$this->codeGeneratorUtil->shouldOverride($filePath)) {
                            $this->io->getInstance()->note(sprintf('File omitted: %s', $filePath));
                            continue;
                        }
                    }
                    $this->codeGeneratorUtil->generateFileWithContent($filePath, $fileContent);
                    $this->io->getInstance()->note(sprintf('File saved: %s', $filePath));
                } catch (Exception $e) {
                    $this->io->getInstance()->error($e->getMessage());
                }
            }
        }
    }
}