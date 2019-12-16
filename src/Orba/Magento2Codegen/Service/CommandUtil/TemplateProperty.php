<?php

namespace Orba\Magento2Codegen\Service\CommandUtil;

use InvalidArgumentException;
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

    public function getAllPropertiesInTemplate(string $templateName): array
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

    public function addProperties(TemplatePropertyBag $propertyBag, array $properties): void
    {
        foreach ($properties as $key => $value) {
            $propertyBag[$key] = $value;
        }
    }

    /**
     * @param string $filePath
     * @return array
     * @throws InvalidArgumentException
     */
    public function getPropertiesFromYamlFile(string $filePath): array
    {
        $data = $this->yamlParser->parseFile($filePath);
        if (!is_array($data)) {
            throw new InvalidArgumentException(
                sprintf('YAML file %s must consists of array.', $filePath)
            );
        }
        foreach ($data as $value) {
            if (!is_scalar($value)) {
                throw new InvalidArgumentException(
                    sprintf('YAML file %s must consists of flat array.', $filePath)
                );
            }
        }
        return $data;
    }
}