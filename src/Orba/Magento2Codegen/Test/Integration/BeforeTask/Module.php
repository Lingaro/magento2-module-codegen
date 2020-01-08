<?php

namespace Orba\Magento2Codegen\Test\Integration\BeforeTask;

use Orba\Magento2Codegen\Service\TaskInterface;
use Orba\Magento2Codegen\Test\Integration\CommandTesterRunner;
use Orba\Magento2Codegen\Test\Integration\TestExtension;

class Module implements TaskInterface
{
    public function execute(): void
    {
        CommandTesterRunner::run(
            'module',
            'EmptyModule',
            [TestExtension::TEST_VENDOR_NAME, 'EmptyModule']
        );
    }
}