<?php

namespace Orba\Magento2Codegen\Service\CommandUtil;

use InvalidArgumentException;
use Orba\Magento2Codegen\Service\TemplateFile;
use Orba\Magento2Codegen\Service\TemplateProcessorInterface;
use Orba\Magento2Codegen\Service\TemplatePropertyMerger;
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

    /**
     * @var TemplatePropertyMerger
     */
    private $templatePropertyMerger;

    public function __construct(
        Parser $yamlParser,
        TemplateFile $templateFile,
        TemplateProcessorInterface $templateProcessor,
        TemplatePropertyMerger $templatePropertyMerger
    )
    {
        $this->yamlParser = $yamlParser;
        $this->templateFile = $templateFile;
        $this->templateProcessor = $templateProcessor;
        $this->templatePropertyMerger = $templatePropertyMerger;
    }

    public function getAllPropertiesInTemplate(string $templateName): array
    {
        $templateNames = array_merge([$templateName], $this->templateFile->getDependencies($templateName, true));
        $templateFiles = $this->templateFile->getTemplateFiles($templateNames);
        $properties = [];
        foreach ($templateFiles as $file) {
            $properties = $this->templatePropertyMerger
                ->merge($properties, $this->templateProcessor->getPropertiesInText($file->getPath()));
            $properties = $this->templatePropertyMerger
                ->merge($properties, $this->templateProcessor->getPropertiesInText($file->getContents()));
        }
        return $properties;
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