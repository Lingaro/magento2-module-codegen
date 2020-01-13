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

    public function __construct(Processor $configProcessor, Parser $yamlParser)
    {
        $this->config = $configProcessor->processConfiguration(
            new Configuration(),
            [$yamlParser->parseFile(BP . '/config/codegen.yml')]
        );
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
}