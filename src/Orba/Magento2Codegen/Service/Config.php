<?php

namespace Orba\Magento2Codegen\Service;

use ArrayAccess;
use Orba\Magento2Codegen\Configuration;
use RuntimeException;
use Symfony\Component\Config\Definition\Processor;
use Symfony\Component\Yaml\Exception\ParseException;
use Symfony\Component\Yaml\Parser;

class Config implements ArrayAccess
{
    private $config;

    public function __construct(
        Processor $configProcessor,
        Parser $yamlParser
    ) {
        try {
            $configPath = '/config/codegen.yml';
            if ($magentoPath = $this->getMagentoPath()) {
                $configPath = $magentoPath;
            }
            $this->setConfig($configProcessor, $yamlParser, $configPath);
        } catch (ParseException $e) {
            $this->setConfig($configProcessor, $yamlParser, '/config/codegen.yml.dist');
        }
    }

    public function offsetExists($offset): bool
    {
        return isset($this->config[$offset]);
    }

    public function offsetGet($offset)
    {
        return isset($this->config[$offset]) ? $this->config[$offset] : null;
    }

    public function offsetSet($offset, $value): void
    {
        throw new RuntimeException('Config is read-only.');
    }

    public function offsetUnset($offset): void
    {
        throw new RuntimeException('Config is read-only.');
    }

    private function setConfig(Processor $configProcessor, Parser $yamlParser, string $filePath): void
    {
        $this->config = $configProcessor->processConfiguration(
            new Configuration(),
            [$yamlParser->parseFile(BP . $filePath)]
        );
    }

    /**
     * @return string|null
     */
    private function getMagentoPath(): ?string
    {
        $path = '/../../../codegen.yml';
        $absolutePath = BP . $path;
        if (file_exists($absolutePath)) {
            return $path;
        }
        return null;
    }
}
