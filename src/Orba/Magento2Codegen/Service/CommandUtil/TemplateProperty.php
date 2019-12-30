<?php

namespace Orba\Magento2Codegen\Service\CommandUtil;

use InvalidArgumentException;
use Orba\Magento2Codegen\Service\TemplateFile;
use Orba\Magento2Codegen\Service\TemplateProcessorInterface;
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
     * @var TemplateProcessorInterface
     */
    private $templateProcessor;

    public function __construct(
        Parser $yamlParser,
        TemplateFile $templateFile,
        TemplateProcessorInterface $templateProcessor
    )
    {
        $this->yamlParser = $yamlParser;
        $this->templateFile = $templateFile;
        $this->templateProcessor = $templateProcessor;
    }

    public function getAllPropertiesInTemplate(string $templateName): array
    {
        $templateNames = array_merge([$templateName], $this->templateFile->getDependencies($templateName, true));
        $templateFiles = $this->templateFile->getTemplateFiles($templateNames);
        $propertiesInTemplate = [];
        foreach ($templateFiles as $file) {
            $propertiesInFilename = $this->templateProcessor->getPropertiesInText($file->getPath());
            $propertiesInCode = $this->templateProcessor->getPropertiesInText($file->getContents());
            $propertiesInTemplate = array_merge($propertiesInTemplate, $propertiesInFilename, $propertiesInCode);
        }
        return $propertiesInTemplate;
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