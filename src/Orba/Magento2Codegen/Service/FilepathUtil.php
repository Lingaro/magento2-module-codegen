<?php

namespace Orba\Magento2Codegen\Service;

use InvalidArgumentException;

class FilepathUtil
{
    public function getAbsolutePath(string $filePath, ?string $rootDir): string
    {
        if (empty($filePath)) {
            throw new InvalidArgumentException('File path must not be empty.');
        }
        if ($filePath[0] === '/') {
            throw new InvalidArgumentException('File path must not start with a slash.');
        }
        if ($rootDir && $rootDir[0] !== '/') {
            throw new InvalidArgumentException('Root dir must start with a slash.');
        }
        return rtrim($rootDir ?: getcwd(), '/') . '/' . ltrim($filePath, '/');
    }

    public function removeTemplateDirFromPath(string $filePath): string
    {
        if (empty($filePath)) {
            throw new InvalidArgumentException('File path must not be empty.');
        }
        if (strpos($filePath, TemplateDir::DIR) !== 0) {
            throw new InvalidArgumentException(sprintf('File path must start with "%s".', TemplateDir::DIR));
        }
        $filePath = str_replace(TemplateDir::DIR, '', $filePath);
        if (!preg_match('/^\/[^\/]*\/(.*)/', $filePath, $matches)) {
            throw new InvalidArgumentException('File path must contain template subdirectory.');
        }
        return $matches[1];
    }
}