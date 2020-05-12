<?php

namespace Orba\Magento2Codegen\Test\Integration;

use Orba\Magento2Codegen\Test\Integration\BeforeTask\ConsoleCommand;

/**
 * @see ConsoleCommand
 */
class ConsoleCommandTest extends TestCase
{
    public function testGeneratedConsoleCommandOutputsDummyText(): void
    {
        $output = $this->execMagentoCommand('test-command');
        $this->assertStringContainsString('Some actions here...', $output[0]);
    }

    public function testGeneratedConsoleCommandHasDescription(): void
    {
        $output = $this->execMagentoCommand('test-command -h');
        $this->assertStringContainsString('writes dummy text', $output[1]);
    }
}