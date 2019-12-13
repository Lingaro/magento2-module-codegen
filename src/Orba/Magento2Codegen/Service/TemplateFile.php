<?php

namespace Orba\Magento2Codegen\Service;

use Orba\Magento2Codegen\Util\TemplatePropertyBag;
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
     * @var TemplatePropertyUtil
     */
    private $propertyUtil;

    public function __construct(
        TemplateDir $templateDir,
        FinderFactory $finderFactory,
        Parser $yamlParser,
        TemplatePropertyUtil $propertyUtil
    )
    {
        $this->templateDir = $templateDir;
        $this->finderFactory = $finderFactory;
        $this->yamlParser = $yamlParser;
        $this->propertyUtil = $propertyUtil;
    }

    public function exists(string $templateName): bool
    {
        return (bool) $this->templateDir->getPath($templateName);
    }

    public function getDescription(string $templateName): string
    {
        $files = $this->finderFactory->create()
            ->name(self::DESCRIPTION_FILENAME)
            ->depth('< 1')
            ->in($this->templateDir->getPath($templateName) . '/' . self::TEMPLATE_CONFIG_FOLDER);
        foreach ($files as $file) {
            /** @var $file SplFileInfo */
            return $file->getContents();
        }
        return '';
    }

    public function getDependencies(string $templateName, bool $nested = false): array
    {
        $dependencies = [];
        $files = $this->finderFactory->create()
            ->name(self::CONFIG_FILENAME)
            ->depth('< 1')
            ->in($this->templateDir->getPath($templateName) . '/' . self::TEMPLATE_CONFIG_FOLDER);
        foreach ($files as $file) {
            /** @var $file SplFileInfo */
            $parsedConfig = $this->yamlParser->parse($file->getContents());
            if (is_array($parsedConfig) && isset($parsedConfig['dependencies'])) {
                $dependencies = $parsedConfig['dependencies'];
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
        $files = $this->finderFactory->create()
            ->name(self::AFTER_GENERATE_FILENAME)
            ->depth('< 1')
            ->in($this->templateDir->getPath($templateName) . '/' . self::TEMPLATE_CONFIG_FOLDER);
        foreach ($files as $file) {
            /** @var $file SplFileInfo */
            return $this->propertyUtil->replacePropertiesInText($file->getContents(), $propertyBag);
        }
        return '';
    }

    /**
     * @param string[] $templateNames
     * @return SplFileInfo[]
     */
    public function getTemplateFiles(array $templateNames): array
    {
        $files = [];
        foreach ($templateNames as $templateName) {
            foreach ($this->finderFactory->create()
                         ->files()
                         ->in($this->templateDir->getPath($templateName)) as $file) {
                $files[] = $file;
            }
        }
        return $files;
    }

    public function getFileName(string $filePath): string
    {
        return pathinfo($filePath)['basename'];
    }

    public function getContent(string $filePath): string
    {
        $pathInfo = pathinfo($filePath);
        foreach ($this->finderFactory->create()
            ->name($pathInfo['basename'])
            ->depth('< 1')
            ->in($pathInfo['dirname']) as $file) {
            /** @var $file SplFileInfo */
            return $file->getContents();
        }
        return '';
    }
}