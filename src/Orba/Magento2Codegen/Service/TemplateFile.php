<?php

namespace Orba\Magento2Codegen\Service;

use InvalidArgumentException;
use Orba\Magento2Codegen\Util\TemplatePropertyBag;
use Symfony\Component\Finder\Exception\DirectoryNotFoundException;
use Symfony\Component\Finder\SplFileInfo;
use Symfony\Component\Yaml\Parser;

class TemplateFile
{
    const TEMPLATE_CONFIG_FOLDER = '.no-copied-config';
    const DESCRIPTION_FILENAME = 'description.txt';
    const CONFIG_FILENAME = 'config.yml';
    const AFTER_GENERATE_FILENAME = 'after-generate-info.txt';

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
        $this->validateTemplateExistence($templateName);
        $file = $this->getFileFromTemplateConfig(self::DESCRIPTION_FILENAME, $templateName);
        return $file ? $file->getContents() : '';
    }

    /**
     * @param string $templateName
     * @param bool $nested
     * @return string[]
     * @throws InvalidArgumentException
     */
    public function getDependencies(string $templateName, bool $nested = false): array
    {
        $this->validateTemplateExistence($templateName);
        $file = $this->getFileFromTemplateConfig(self::CONFIG_FILENAME, $templateName);
        if (!$file) {
            return [];
        }
        $dependencies = [];
        $parsedConfig = $this->yamlParser->parse($file->getContents());
        if (!is_array($parsedConfig)) {
            throw new InvalidArgumentException(sprintf('Invalid config file: %s', $file->getPath()));
        }
        if (isset($parsedConfig['dependencies'])) {
            $dependencies = $parsedConfig['dependencies'];
            foreach ($dependencies as $dependency) {
                if (!is_scalar($dependency)) {
                    throw new InvalidArgumentException(
                        sprintf('Invalid dependencies array in: %s', $file->getPath())
                    );
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

    public function getManualSteps(string $templateName, TemplatePropertyBag $propertyBag): string
    {
        $this->validateTemplateExistence($templateName);
        $file = $this->getFileFromTemplateConfig(self::AFTER_GENERATE_FILENAME, $templateName);
        return $file ? $this->templateProcessor->replacePropertiesInText($file->getContents(), $propertyBag) : '';
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
}