<?php

/**
 * Copyright © 2023 Lingaro sp. z o.o. All rights reserved.
 * See LICENSE for license details.
 */

declare(strict_types=1);

namespace Lingaro\Magento2Codegen\Service;

use Symfony\Component\Filesystem\Filesystem;
use Lingaro\Magento2Codegen\Command\Template\GenerateCommand;

use function sprintf;

class CodeGeneratorUtil
{
    private Filesystem $filesystem;
    private FilepathUtil $filepathUtil;
    private IO $io;
    private TemplateDir $templateDir;

    public function __construct(
        Filesystem $filesystem,
        FilepathUtil $filepathUtil,
        IO $io,
        TemplateDir $templateDir
    ) {
        $this->filesystem = $filesystem;
        $this->filepathUtil = $filepathUtil;
        $this->io = $io;
        $this->templateDir = $templateDir;
    }

    public function getDestinationFilePath(string $filePath, string $rootDir): string
    {
        return $this->filepathUtil->getAbsolutePath(
            $this->filepathUtil->removeTemplateDirFromPath(
                $filePath,
                $this->templateDir->getTemplateRepositoryDirectories()
            ),
            $rootDir
        );
    }

    public function canCopyWithoutOverriding(string $filePath): bool
    {
        return !$this->filesystem->exists($filePath);
    }

    public function shouldMerge(string $filePath, bool $isMergerExperimental, ?string $forceMerge = null): bool
    {
        if ($forceMerge === GenerateCommand::FORCE_MERGE_ALL) {
            return true;
        } elseif ($forceMerge === GenerateCommand::FORCE_MERGE_NONEXPERIMENTAL && $isMergerExperimental === false) {
            return true;
        }
        return $this->io->getInstance()->confirm(
            sprintf('%s already exists, would you like to perform a merge?', $filePath)
            . ($isMergerExperimental
                ? "\n <fg=yellow>Watchout! Experimental merger will be used. You will probably need to "
                . "clean the file a little bit after merge.</>"
                : ''),
            !$isMergerExperimental
        );
    }

    public function shouldOverride(string $filePath): bool
    {
        if ($this->io->getInput()->getOption(GenerateCommand::OPTION_FORCE_OVERRIDE)) {
            return true;
        }
        return $this->io->getInstance()->confirm(
            sprintf('%s already exists, would you like to overwrite it?', $filePath),
            false
        );
    }

    public function generateFileWithContent(string $filePath, string $fileContent): void
    {
        $this->filesystem->dumpFile($filePath, $fileContent);
    }
}
