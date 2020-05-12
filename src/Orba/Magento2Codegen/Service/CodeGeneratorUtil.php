<?php

namespace Orba\Magento2Codegen\Service;

use Symfony\Component\Filesystem\Filesystem;

class CodeGeneratorUtil
{
    /**
     * @var Filesystem
     */
    private $filesystem;

    /**
     * @var FilepathUtil
     */
    private $filepathUtil;

    /**
     * @var IO
     */
    private $io;

    public function __construct(Filesystem $filesystem, FilepathUtil $filepathUtil, IO $io)
    {
        $this->filesystem = $filesystem;
        $this->filepathUtil = $filepathUtil;
        $this->io = $io;
    }

    public function getDestinationFilePath(string $filePath, string $rootDir): string
    {
        return $this->filepathUtil->getAbsolutePath(
            $this->filepathUtil->removeTemplateDirFromPath($filePath),
            $rootDir
        );
    }

    public function canCopyWithoutOverriding(string $filePath): bool
    {
        return !$this->filesystem->exists($filePath);
    }

    public function shouldMerge(string $filePath, bool $isMergerExperimental): bool
    {
        return $this->io->getInstance()->confirm(
            sprintf('%s already exists, would you like to perform a merge?', $filePath)
            . ($isMergerExperimental
                ? "\n <fg=yellow>Watchout! Experimental merger will be used. You will probably need to clean the file a little bit after merge.</>"
                : ''),
            !$isMergerExperimental
        );
    }

    public function shouldOverride(string $filePath): bool
    {
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