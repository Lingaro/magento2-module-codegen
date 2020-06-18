<?php

namespace Orba\Magento2Codegen\Service;

use InvalidArgumentException;
use Symfony\Component\Finder\Exception\DirectoryNotFoundException;
use Symfony\Component\Finder\SplFileInfo;
use Symfony\Component\Yaml\Parser;

class TemplateFile
{
    const TEMPLATE_CONFIG_FOLDER = '.no-copied-config';
    const CONFIG_FILENAME = 'config.yml';

    /**
     * @var TemplateDir
     */
    private $templateDir;

    /**
     * @var FinderFactory
     */
    private $finderFactory;

    /**
     * @var Parser
     */
    private $yamlParser;

    /**
     * @var TemplateProcessorInterface
     */
    private $templateProcessor;

    public function __construct(
        TemplateDir $templateDir,
        FinderFactory $finderFactory,
        Parser $yamlParser,
        TemplateProcessorInterface $templateProcessor
    )
    {
        $this->templateDir = $templateDir;
        $this->finderFactory = $finderFactory;
        $this->yamlParser = $yamlParser;
        $this->templateProcessor = $templateProcessor;
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
     * @param string $templateName
     * @param bool $nested
     * @return string[]
     * @throws InvalidArgumentException
     */
    public function getDependencies(string $templateName, bool $nested = false): array
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
        if ($nested) {
            foreach ($dependencies as $dependency) {
                $dependencies = array_merge($dependencies, $this->getDependencies($dependency, true));
            }
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

    /**
     * @param string[] $templateNames
     * @return SplFileInfo[]
     */
    public function getTemplateFiles(array $templateNames): array
    {
        $files = [];
        foreach ($templateNames as $templateName) {
            $this->validateTemplateExistence($templateName);
            foreach ($this->finderFactory->create()
                         ->files()
                         ->in($this->templateDir->getPath($templateName)) as $file) {
                $files[] = $file;
            }
        }
        return $files;
    }

    private function getRootConfig(string $templateName, string $configName): string
    {
        $parsedConfig = $this->getParsedConfig($templateName);
        return isset($parsedConfig[$configName]) ? $parsedConfig[$configName] : '';
    }

    /**
     * @param string $templateName
     */
    private function validateTemplateExistence(string $templateName): void
    {
        if (!$this->exists($templateName)) {
            throw new InvalidArgumentException(sprintf('Template does not exist: %s', $templateName));
        }
    }

    private function getFileFromTemplateConfig(string $fileName, string $templateName):? SplFileInfo
    {
        try {
            $files = $this->finderFactory->create()
                ->name($fileName)
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

    private function getParsedConfig(string $templateName)
    {
        $this->validateTemplateExistence($templateName);
        $file = $this->getFileFromTemplateConfig(self::CONFIG_FILENAME, $templateName);
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
