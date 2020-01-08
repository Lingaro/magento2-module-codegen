<?php

namespace Orba\Magento2Codegen\Service\CommandUtil;

use InvalidArgumentException;
use Orba\Magento2Codegen\Application;
use Orba\Magento2Codegen\Service\IO;
use Orba\Magento2Codegen\Service\TemplateDir;
use Orba\Magento2Codegen\Service\TemplateFile;
use Orba\Magento2Codegen\Service\TemplateProcessorInterface;
use Orba\Magento2Codegen\Service\TemplatePropertyBagFactory;
use Orba\Magento2Codegen\Service\TemplatePropertyMerger;
use Orba\Magento2Codegen\Util\TemplatePropertyBag;
use Symfony\Component\Yaml\Parser;

/**
 * Manage properties
 * uses $templateName which is set during creation of object of this class
 * Class TemplateProperty
 * @package Orba\Magento2Codegen\Service\CommandUtil
 */
class TemplateProperty
{
    private const PROPERTIES_EXTENSION_FILENAME = 'properties-extension.yml';
    private const DEFAULT = 'default';
    private const DESCRIPTION = 'description';

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

    /** @var TemplateDir */
    private $templateDir;

    /** @var IO */
    private $io;

    /** @var TemplatePropertyBagFactory */
    private $propertyBagFactory;

    /** @var string */
    private $templateName;

    /** @var null|array */
    private $properties = null;

    /**
     * Two dimensional array, extension of properties,
     * first key is property
     * second key is in EXTENSION_KEYS (and in file PROPERTIES_EXTENSION_FILENAME)
     * @var null|array
     */
    private $extensions = [];

    /**
     * Helper - keys used as second key in $extension in this app
     * (not all keys loaded from file PROPERTIES_EXTENSION_FILENAME)
     */
    private const EXTENSION_KEYS = [self::DEFAULT => self::DEFAULT, self::DESCRIPTION => self::DESCRIPTION];

    /**
     * TemplateProperty constructor.
     * @param Parser $yamlParser
     * @param TemplateFile $templateFile
     * @param TemplateProcessorInterface $templateProcessor
     * @param TemplatePropertyMerger $templatePropertyMerger
     * @param TemplateDir $templateDir
     * @param TemplatePropertyBagFactory $propertyBagFactory
     * @param IO $io
     */
    public function __construct(
        Parser $yamlParser,
        TemplateFile $templateFile,
        TemplateProcessorInterface $templateProcessor,
        TemplatePropertyMerger $templatePropertyMerger,
        TemplateDir $templateDir,
        TemplatePropertyBagFactory $propertyBagFactory,
        IO $io
    ) {
        $this->yamlParser = $yamlParser;
        $this->templateFile = $templateFile;
        $this->templateProcessor = $templateProcessor;
        $this->templatePropertyMerger = $templatePropertyMerger;
        $this->templateDir = $templateDir;
        $this->propertyBagFactory = $propertyBagFactory;
        $this->io = $io;
    }

    /**
     * Method because symfony...
     * Must be called on this object - if not, propertyName is set to default from services.yml
     * @param string $templateName
     * @return static
     */
    public function withTemplateName(string $templateName)
    {
        $new = clone $this;
        $new->templateName = $templateName;
        return $new;
    }

    /**
     * Gives all properties
     * @return array
     */
    private function getAllPropertiesInTemplate(): array
    {
        $templateNames = array_merge([$this->templateName], $this->templateFile->getDependencies($this->templateName, true));
        $templateFiles = $this->templateFile->getTemplateFiles($templateNames);
        $this->properties = [];
        foreach ($templateFiles as $file) {
            $this->properties = $this->templatePropertyMerger
                ->merge($this->properties, $this->templateProcessor->getPropertiesInText($file->getPath()));
            $this->properties = $this->templatePropertyMerger
                ->merge($this->properties, $this->templateProcessor->getPropertiesInText($file->getContents()));
        }
        return $this->properties;
    }

    /**
     * @param string $filePath
     * @return array
     * @throws InvalidArgumentException
     */
    private function getPropertiesFromYamlFile(string $filePath): array
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

    /**
     * Returns default property value
     * @param string $propertyKey
     * @return string|null
     */
    private function getDefault(string $propertyKey): ?string
    {
        if (!array_key_exists($propertyKey, $this->extensions)) {
            $this->loadExtensions();
        }
        return $this->extensions[$propertyKey][self::EXTENSION_KEYS[self::DEFAULT]];
    }

    /**
     * Returns description of property
     * @param string $propertyKey
     * @return string|null
     */
    private function getDescription(string $propertyKey): ?string
    {
        if (!array_key_exists($propertyKey, $this->extensions)) {
            $this->loadExtensions();
        }
        return $this->extensions[$propertyKey][self::EXTENSION_KEYS[self::DESCRIPTION]];
    }

    /**
     * Read from file PROPERTIES_EXTENSION_FILENAME additional information about properties in the given template
     * but set only information which has key specified in EXTENSION_KEYS
     * @return void
     */
    private function loadExtensions(): void
    {
        if (is_null($this->properties)) {
            $this->getAllPropertiesInTemplate();
        }
        $path = $this->templateDir->getPath($this->templateName)
            . '/' . TemplateFile::TEMPLATE_CONFIG_FOLDER
            . '/' . self::PROPERTIES_EXTENSION_FILENAME;
        $this->nullExtensions();
        if (is_file($path)) {
            $data = $this->yamlParser->parseFile(
                $this->templateDir->getPath($this->templateName)
                . '/' . TemplateFile::TEMPLATE_CONFIG_FOLDER
                . '/' . self::PROPERTIES_EXTENSION_FILENAME
            );
            $this->fillExtensions($data);
        }
    }

    /**
     * Fill $this->extensions array
     * Set only information which has key specified in EXTENSION_KEYS
     * @param array $data
     * @return void
     */
    private function fillExtensions(array $data): void
    {
        foreach ($this->properties as $propertyName => $propertyVal) {
            if (array_key_exists($propertyName, $data)) {
                foreach ($data[$propertyName] as $key => $value) {
                    if (array_key_exists($key, self::EXTENSION_KEYS)) {
                        $this->extensions[$propertyName][$key] = $value;
                    }
                }
            }
        }
    }

    /**
     * Null only extension which are defined in EXTENSION_KEYS
     * Mainly to have all keys in $this->extension[$property]
     * @return void
     */
    private function nullExtensions(): void
    {
        foreach ($this->properties as $propertyName => $propertyValue) {
            foreach (self::EXTENSION_KEYS as $key) {
                $this->extensions[$propertyName][$key] = null;
            }
        }
    }

    /**
     * Collect all properties from templates and paths and ask about values
     * @param TemplatePropertyBag|null $basePropertyBag
     * @return TemplatePropertyBag
     */
    public function prepareProperties(?TemplatePropertyBag $basePropertyBag = null): TemplatePropertyBag
    {
        $propertyBag = $basePropertyBag ?: $this->propertyBagFactory->create();
        $propertyBag->add($this->getPropertiesFromYamlFile(
            BP . '/' . Application::CONFIG_FOLDER . '/' . Application::DEFAULT_PROPERTIES_FILENAME
        ));
        $templateProperties = $this->getAllPropertiesInTemplate();
        foreach ($templateProperties as $key => $elements) {
            if (!isset($propertyBag[$key])) {
                if (is_array($elements)) {
                    $items = [];
                    $i = 0;
                    do {
                        $item = [];
                        $this->displayPropertyDescription($key);
                        foreach ($elements as $element) {
                            $item[$element] = $this->io->getInstance()->ask($key . '.' . $i . '.' . $element, $this->getDefault($key));
                        }
                        $items[] = $item;
                        $i++;
                    } while ($this->io->getInstance()->confirm(sprintf('Do you want to add another item to "%s" array?', $key), true));
                    $propertyBag->add([$key => $items]);
                } else {
                    $this->displayPropertyDescription($key);
                    $propertyBag->add([$key => $this->io->getInstance()->ask($key, $this->getDefault($key))]);
                }
            }
        }
        return $propertyBag;
    }

    /**
     * Display description for property if exists
     * @param string $key - property
     * @return void
     */
    private function displayPropertyDescription(string $key): void
    {
        $description = $this->getDescription($key);
        if ($description) {
            $this->io->getInstance()->block($description, $key . ' description');
        }
    }
}
