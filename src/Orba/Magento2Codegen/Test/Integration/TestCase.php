<?php

namespace Orba\Magento2Codegen\Test\Integration;

class TestCase extends \PHPUnit\Framework\TestCase
{
    /**
     * @param string $input
     * @return string[]
     */
    public function execMagentoCommand(string $input): array
    {
        exec($_ENV['MAGENTO_CONSOLE_COMMAND_PATH'] . ' ' . $input, $output);
        return $output;
    }
}