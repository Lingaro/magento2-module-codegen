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

    private $coreDirectories = [
        [
            'path' => self::DIR
        ]
    ];

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
        $directories = $this->getDirectories();
        $dir = isset($directories[$templateName]) ? $directories[$templateName] : self::DIR;
        $path = $dir . '/' . $templateName;
        if ($this->filesystem->exists($path)) {
            return $path;
        }
        return null;
    }

    public function getTemplateNames(): array
    {
        return array_keys($this->getDirectories());
    }

    private function getDirectories(): array
    {
        if (is_array($this->directories)) {
            return $this->directories;
        }
        $this->directories = $directories = [];
        if (isset($this->config['templateDirectories'])) {
            $directories = array_merge($this->coreDirectories, $this->config['templateDirectories']);
        }
        foreach ($directories as $directory) {
            $path = str_replace(BP, '', $directory['path']);
            $absolutePath = BP . '/' . trim($path, '/');
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