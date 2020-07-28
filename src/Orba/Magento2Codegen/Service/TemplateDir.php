<?php

namespace Orba\Magento2Codegen\Service;

use Symfony\Component\Filesystem\Filesystem;

class TemplateDir
{
    const DIR = BP . '/templates';

    /**
     * @var Config
     */
    private $config;

    /**
     * @var Filesystem
     */
    private $filesystem;

    /**
     * @var DirectoryIteratorFactory
     */
    private $directoryIteratorFactory;

    /**
     * @var array|null
     */
    private $directories;

    public function __construct(
        Config $config,
        DirectoryIteratorFactory $directoryIteratorFactory,
        Filesystem $filesystem
    ) {
        $this->config = $config;
        $this->filesystem = $filesystem;
        $this->directoryIteratorFactory = $directoryIteratorFactory;

    }

    public function getPath(string $templateName): ?string
    {
        $directories = $this->getAllTemplateDirectories();
        $dir = isset($directories[$templateName]) ? $directories[$templateName] : self::DIR;
        $path = $dir . '/' . $templateName;
        if ($this->filesystem->exists($path)) {
            return $path;
        }
        return null;
    }

    /**
     * @return string[]
     */
    public function getTemplateNames(): array
    {
        return array_keys($this->getAllTemplateDirectories());
    }

    /**
     * @return string[]
     */
    public function getTemplateRepositoryDirectories(): array
    {
        $directories = [self::DIR];
        if (isset($this->config['templateDirectories'])) {
            foreach ($this->config['templateDirectories'] as $directory) {
                $directories[] = BP . '/' . $directory['path'];
            }
        }
        return $directories;
    }

    /**
     * @return string[]
     */
    private function getAllTemplateDirectories(): array
    {
        if (is_array($this->directories)) {
            return $this->directories;
        }
        $this->directories = [];
        foreach ($this->getTemplateRepositoryDirectories() as $directory) {
            $path = str_replace(BP, '', $directory);
            $absolutePath = BP . '/' . ltrim($path, '/');
            $directoryIterator = $this->directoryIteratorFactory->create($absolutePath);
            foreach ($directoryIterator as $dir) {
                if ($dir->isDir()) {
                    $this->directories[$dir->getFilename()] = $absolutePath;
                }
            }
        }
        return $this->directories;
    }
}
