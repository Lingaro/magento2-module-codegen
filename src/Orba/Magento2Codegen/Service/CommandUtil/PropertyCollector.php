<?php

/**
 * @copyright Copyright © 2022 Orba. All rights reserved.
 * @author    info@orba.co
 */

declare(strict_types=1);

namespace Orba\Magento2Codegen\Service\CommandUtil;

use Orba\Magento2Codegen\Command\Template\GenerateCommand;
use Orba\Magento2Codegen\Service\IO;
use Orba\Magento2Codegen\Service\PropertyValueCollector\Yaml\DataProviderFactory;
use Orba\Magento2Codegen\Service\PropertyValueCollector\Yaml\DataProviderRegistry;

class PropertyCollector
{
    public const PROPERTY_COLLECTOR_CONSOLE = 'console';
    public const PROPERTY_COLLECTOR_YAML = 'yaml';

    private IO $io;
    private DataProviderFactory $yamlDataProviderFactory;
    private DataProviderRegistry $yamlDataProviderRegistry;

    public function __construct(
        IO $io,
        DataProviderFactory $yamlDataProviderFactory,
        DataProviderRegistry $yamlDataProviderRegistry
    ) {
        $this->io = $io;
        $this->yamlDataProviderFactory = $yamlDataProviderFactory;
        $this->yamlDataProviderRegistry = $yamlDataProviderRegistry;
    }

    public function handleAdditionalLogic(): void
    {
        $yamlPath = $this->io->getInput()->getOption(GenerateCommand::OPTION_YAML_PATH);
        if ($yamlPath) {
            $dataProvider = $this->yamlDataProviderFactory->create($yamlPath);
            $this->yamlDataProviderRegistry->set($dataProvider);
        }
    }

    public function getType(): string
    {
        if ($this->io->getInput()->getOption(GenerateCommand::OPTION_YAML_PATH)) {
            return self::PROPERTY_COLLECTOR_YAML;
        }
        return self::PROPERTY_COLLECTOR_CONSOLE;
    }
}
