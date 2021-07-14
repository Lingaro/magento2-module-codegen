<?php

/**
 * @copyright Copyright © 2021 Orba. All rights reserved.
 * @author    info@orba.co
 */

declare(strict_types=1);

namespace Orba\Magento2Codegen\Service;

use Symfony\Component\Filesystem\Filesystem;

use function array_keys;
use function is_array;
use function str_replace;

class TemplateDir
{
    public const DIR = BP . '/templates';

    private Config $config;
    private Filesystem $filesystem;
    private DirectoryIteratorFactory $directoryIteratorFactory;
    private ?array $directories = null;

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
        $dir = $directories[$templateName] ?? self::DIR;
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
