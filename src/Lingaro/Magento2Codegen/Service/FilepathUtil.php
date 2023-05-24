<?php

/**
 * Copyright © 2023 Lingaro sp. z o.o. All rights reserved.
 * See LICENSE for license details.
 */

declare(strict_types=1);

namespace Lingaro\Magento2Codegen\Service;

use InvalidArgumentException;

use function file_exists;
use function file_get_contents;
use function is_null;
use function ltrim;
use function pathinfo;
use function preg_match;
use function rtrim;
use function sprintf;
use function str_replace;
use function strpos;

class FilepathUtil
{
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
                'Specified file path does not have any file name: ' . $filePath
            );
        }
        return $fileName;
    }

    public function getFilePath(string $filePath): string
    {
        if (!file_exists($filePath)) {
            throw new InvalidArgumentException(
                sprintf('Specified file path does not have any file name: %s', $filePath)
            );
        }
        return $filePath;
    }

    /**
     * @SuppressWarnings(PHPMD.ErrorControlOperator)
     */
    public function getContent(string $filePath): string
    {
        return @file_get_contents($filePath) ?: '';
    }
}
