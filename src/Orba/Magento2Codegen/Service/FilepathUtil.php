<?php

namespace Orba\Magento2Codegen\Service;

use InvalidArgumentException;

class FilepathUtil
{
    /**
     * @var FinderFactory
     */
    private $finderFactory;

    public function __construct(FinderFactory $finderFactory)
    {
        $this->finderFactory = $finderFactory;
    }

    public function getAbsolutePath(string $filePath, string $rootDir): string
    {
        if (empty($filePath)) {
            throw new InvalidArgumentException('File path must not be empty.');
        }
        if (empty($rootDir)) {
            throw new InvalidArgumentException('Root dir must not be empty.');
        }
        if ($filePath[0] === '/') {
            throw new InvalidArgumentException('File path must not start with a slash.');
        }
        if ($rootDir[0] !== '/') {
            throw new InvalidArgumentException('Root dir must start with a slash.');
        }
        return rtrim($rootDir, '/') . '/' . ltrim($filePath, '/');
    }

    public function removeTemplateDirFromPath(string $filePath, array $templateRepositoryDirs): string
    {
        if (empty($filePath)) {
            throw new InvalidArgumentException('File path must not be empty.');
        }
        if (empty($templateRepositoryDirs)) {
            throw new InvalidArgumentException('Template repository dirs array must not be empty.');
        }
        $templateRepositoryDir = null;
        foreach ($templateRepositoryDirs as $dir) {
            if (strpos($filePath, $dir) === 0) {
                $templateRepositoryDir = $dir;
                break;
            }
        }
        if (is_null($templateRepositoryDir)) {
            throw new InvalidArgumentException('File path must start with template repository dir.');
        }

        $filePath = str_replace($templateRepositoryDir, '', $filePath);
        if (!preg_match('/^\/[^\/]*\/(.*)/', $filePath, $matches)) {
            throw new InvalidArgumentException('File path must contain template subdirectory.');
        }
        return $matches[1];
    }

    public function getFileName(string $filePath): string
    {
        $fileName = pathinfo($filePath)['basename'];
        if (!$fileName) {
            throw new InvalidArgumentException(
                sprintf('Specified file path does not have any file name: %s', $filePath)
            );
        }
        return $fileName;
    }

    /**
     * @param string $filePath
     * @return string
     */
    public function getFilePath(string $filePath): string
    {
        if (!file_exists($filePath)) {
            throw new InvalidArgumentException(
                sprintf('Specified file path does not have any file name: %s', $filePath)
            );
        }
        return $filePath;
    }

    public function getContent(string $filePath): string
    {
        return @file_get_contents($filePath) ?: '';
    }
}
