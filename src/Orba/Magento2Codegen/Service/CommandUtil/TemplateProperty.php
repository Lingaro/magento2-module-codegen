<?php

namespace Orba\Magento2Codegen\Service\CommandUtil;

use InvalidArgumentException;
use Orba\Magento2Codegen\Application;
use Orba\Magento2Codegen\Helper\IO;
use Orba\Magento2Codegen\Service\TemplateFile;
use Orba\Magento2Codegen\Service\TemplatePropertyUtil;
use Orba\Magento2Codegen\Util\TemplatePropertyBag;
use Symfony\Component\Yaml\Parser;

class TemplateProperty
{
    /**
     * @var Parser
     */
    private $yamlParser;

    /**
     * @var TemplateFile
     */
    private $templateFile;

    /**
     * @var TemplatePropertyUtil
     */
    private $propertyUtil;

    public function __construct(Parser $yamlParser, TemplateFile $templateFile, TemplatePropertyUtil $propertyUtil)
    {
        $this->yamlParser = $yamlParser;
        $this->templateFile = $templateFile;
        $this->propertyUtil = $propertyUtil;
    }

    public function setDefaultProperties(TemplatePropertyBag $propertyBag): void
    {
        $this->addProperties(
            $propertyBag,
            $this->yamlParser->parseFile(
                BP . '/' . Application::CONFIG_FOLDER . '/' . Application::DEFAULT_PROPERTIES_FILENAME
            )
        );
    }

    public function askAndSetInputPropertiesForTemplate(
        TemplatePropertyBag $propertyBag,
        string $templateName,
        IO $io
    ): void
    {
        $templateProperties = $this->getAllPropertiesInTemplate($templateName);
        foreach ($templateProperties as $property) {
            if (!isset($propertyBag[$property])) {
                $this->addProperties($propertyBag, [$property => $io->ask($property)]);
            }
        }
    }

    private function getAllPropertiesInTemplate(string $templateName): array
    {
        $templateNames = array_merge([$templateName], $this->templateFile->getDependencies($templateName, true));
        $templateFiles = $this->templateFile->getTemplateFiles($templateNames);
        $propertiesInTemplate = [];
        foreach ($templateFiles as $file) {
            $propertiesInFilename = $this->propertyUtil->getPropertiesInText($file->getPath());
            $propertiesInCode = $this->propertyUtil->getPropertiesInText($file->getContents());
            $propertiesInTemplate = array_unique(
                array_merge($propertiesInTemplate, $propertiesInFilename, $propertiesInCode)
            );
        }
        return $propertiesInTemplate;
    }

    /**
     * @param TemplatePropertyBag $propertyBag
     * @param mixed $properties
     * @throws InvalidArgumentException
     */
    private function addProperties(TemplatePropertyBag $propertyBag, $properties): void
    {
        if (!is_array($properties)) {
            throw new InvalidArgumentException('Properties must be an array.');
        }
        foreach ($properties as $key => $value) {
            $propertyBag[$key] = $value;
        }
    }
}