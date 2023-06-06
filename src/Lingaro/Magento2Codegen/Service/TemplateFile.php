<?php

/**
 * Copyright © 2023 Lingaro sp. z o.o. All rights reserved.
 * See LICENSE for license details.
 */

declare(strict_types=1);

namespace Lingaro\Magento2Codegen\Service;

use InvalidArgumentException;
use Symfony\Component\Finder\Exception\DirectoryNotFoundException;
use Symfony\Component\Finder\SplFileInfo;
use Symfony\Component\Yaml\Parser;

use function array_merge;
use function is_array;
use function is_scalar;
use function sprintf;

class TemplateFile
{
    private const TEMPLATE_CONFIG_FOLDER = '.no-copied-config';
    private const CONFIG_FILENAME = 'config.yml';

    private TemplateDir $templateDir;
    private FinderFactory $finderFactory;
    private Parser $yamlParser;

    public function __construct(TemplateDir $templateDir, FinderFactory $finderFactory, Parser $yamlParser)
    {
        $this->templateDir = $templateDir;
        $this->finderFactory = $finderFactory;
        $this->yamlParser = $yamlParser;
    }

    public function exists(string $templateName): bool
    {
        return (bool) $this->templateDir->getPath($templateName);
    }

    public function getDescription(string $templateName): string
    {
        return $this->getRootConfig($templateName, 'description');
    }

    /**
     * @return string[]
     * @throws InvalidArgumentException
     */
    public function getDependencies(string $templateName): array
    {
        $parsedConfig = $this->getParsedConfig($templateName);
        $dependencies = [];
        if (isset($parsedConfig['dependencies'])) {
            $dependencies = $parsedConfig['dependencies'];
            foreach ($dependencies as $dependency) {
                if (!is_scalar($dependency)) {
                    throw new InvalidArgumentException('Invalid dependencies array');
                }
                if (!$this->exists($dependency)) {
                    throw new InvalidArgumentException(
                        sprintf('Dependency does not exist: %s', $dependency)
                    );
                }
            }
        }
        foreach ($dependencies as $dependency) {
            $dependencies = array_merge($dependencies, $this->getDependencies($dependency));
        }
        return $dependencies;
    }

    public function getPropertiesConfig(string $templateName): array
    {
        $parsedConfig = $this->getParsedConfig($templateName);
        $properties = [];
        if (isset($parsedConfig['properties'])) {
            $properties = $parsedConfig['properties'];
        }
        return $properties;
    }

    public function getAfterGenerateConfig(string $templateName): string
    {
        return $this->getRootConfig($templateName, 'afterGenerate');
    }

    public function getType(string $templateName): string
    {
        return $this->getRootConfig($templateName, 'type');
    }

    public function getIsAbstract(string $templateName): bool
    {
        return (bool) $this->getRootConfig($templateName, 'isAbstract');
    }

    /**
     * @param string[] $templateNames
     * @return SplFileInfo[]
     */
    public function getTemplateFiles(array $templateNames): array
    {
        $files = [];
        foreach ($templateNames as $templateName) {
            $this->validateTemplateExistence($templateName);
            foreach (
                $this->finderFactory->create()
                         ->files()
                         ->in($this->templateDir->getPath($templateName)) as $file
            ) {
                $files[] = $file;
            }
        }
        return $files;
    }

    private function getRootConfig(string $templateName, string $configName): string
    {
        $parsedConfig = $this->getParsedConfig($templateName);
        return $parsedConfig[$configName] ?? '';
    }

    private function validateTemplateExistence(string $templateName): void
    {
        if (!$this->exists($templateName)) {
            throw new InvalidArgumentException(sprintf('Template does not exist: %s', $templateName));
        }
    }

    private function getFileFromTemplateConfig(string $templateName): ?SplFileInfo
    {
        try {
            $files = $this->finderFactory->create()
                ->name(self::CONFIG_FILENAME)
                ->depth('< 1')
                ->in($this->templateDir->getPath($templateName) . '/' . self::TEMPLATE_CONFIG_FOLDER);
        } catch (DirectoryNotFoundException $e) {
            return null;
        }
        foreach ($files as $file) {
            return $file;
        }
        return null;
    }

    private function getParsedConfig(string $templateName): array
    {
        $this->validateTemplateExistence($templateName);
        $file = $this->getFileFromTemplateConfig($templateName);
        if (!$file) {
            return [];
        }
        $parsedConfig = $this->yamlParser->parse($file->getContents());
        if (!is_array($parsedConfig)) {
            throw new InvalidArgumentException(sprintf('Invalid config file: %s', $file->getPath()));
        }
        return $parsedConfig;
    }
}
