<?php

namespace Orba\Magento2Codegen\Test\Integration\BeforeTask;

use Orba\Magento2Codegen\Service\TaskInterface;
use Orba\Magento2Codegen\Test\Integration\CommandTesterRunner;
use Orba\Magento2Codegen\Test\Integration\TestExtension;

class ConsoleCommand implements TaskInterface
{
    public function execute(): void
    {
        CommandTesterRunner::run(
            'module',
            'ConsoleCommand',
            [TestExtension::TEST_VENDOR_NAME, 'ConsoleCommand']
        );
        CommandTesterRunner::run(
            'consoleCommand',
            'ConsoleCommand',
            ['test command', 'writes dummy text']
        );
    }
}