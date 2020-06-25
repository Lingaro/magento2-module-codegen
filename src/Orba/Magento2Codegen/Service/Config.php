<?php

namespace Orba\Magento2Codegen\Service;

use ArrayAccess;
use Orba\Magento2Codegen\Configuration;
use RuntimeException;
use Symfony\Component\Config\Definition\Processor;
use Symfony\Component\Yaml\Parser;

class Config implements ArrayAccess
{
    private $config;

    public function __construct(
        Processor $configProcessor,
        Parser $yamlParser
    ) {
        $this->setConfig($configProcessor, $yamlParser, $this->getConfigPath());
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

    private function getConfigPath(): string
    {
        $result = '/config/codegen.yml.dist';
        if ($path = $this->getPath('/../../../codegen.yml')) {
            $result = $path;
        } elseif ($path = $this->getPath('/config/codegen.yml')) {
            $result = $path;
        }
        return $result;
    }

    private function getPath(string $relativePath): ?string
    {
        if (file_exists(BP . $relativePath)) {
            return $relativePath;
        }
        return null;
    }
}
