<?php

/**
 * Copyright © 2023 Lingaro sp. z o.o. All rights reserved.
 * See LICENSE for license details.
 */

declare(strict_types=1);

namespace Orba\Magento2Codegen\Service;

use ArrayAccess;
use Orba\Magento2Codegen\Configuration;
use RuntimeException;
use Symfony\Component\Config\Definition\Processor;
use Symfony\Component\Yaml\Parser;

use function file_exists;

class Config implements ArrayAccess
{
    private array $config;

    public function __construct(Processor $configProcessor, Parser $yamlParser)
    {
        $this->setConfig($configProcessor, $yamlParser, $this->getConfigPath());
    }

    public function offsetExists($offset): bool
    {
        return isset($this->config[$offset]);
    }

    #[\ReturnTypeWillChange]
    public function offsetGet($offset)
    {
        return $this->config[$offset] ?? null;
    }

    /**
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function offsetSet($offset, $value): void
    {
        throw new RuntimeException('Config is read-only.');
    }

    /**
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
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
        return $this->getPath('/../../../codegen.yml')
            ?? ($this->getPath('/config/codegen.yml') ?? '/config/codegen.yml.dist');
    }

    private function getPath(string $relativePath): ?string
    {
        if (file_exists(BP . $relativePath)) {
            return $relativePath;
        }
        return null;
    }
}
