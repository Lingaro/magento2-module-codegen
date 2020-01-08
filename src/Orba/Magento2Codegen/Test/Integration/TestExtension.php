<?php

namespace Orba\Magento2Codegen\Test\Integration;

use Orba\Magento2Codegen\Service\TasksExecutor;
use PHPUnit\Runner\AfterLastTestHook;
use PHPUnit\Runner\BeforeFirstTestHook;
use RuntimeException;
use Symfony\Component\Finder\Finder;

class TestExtension implements BeforeFirstTestHook, AfterLastTestHook
{
    const TEST_VENDOR_NAME = 'Codegen';

    public function __construct()
    {
        define('BP', dirname(__DIR__, 5));
    }

    public function executeBeforeFirstTest(): void
    {
        $this->checkEnvParams();
        $this->removeGeneratedFiles();
        $this->executeTasks('Before');
        $this->runMagentoSetupUpgrade();
    }

    public function executeAfterLastTest(): void
    {
        $this->executeTasks('After');
        $this->removeGeneratedFiles();
        $this->runMagentoSetupUpgrade();
    }

    private function checkEnvParams(): void
    {
        foreach ([
                     'MAGENTO_ROOT_DIR',
                     'MAGENTO_CONSOLE_COMMAND_PATH',
                     'MAGENTO_BASE_URL'
                 ] as $param) {
            if (empty($_ENV[$param])) {
                throw new RuntimeException(sprintf('PHP env param "%s" not set in PHPUnit config.', $param));
            }
        }
    }

    private function executeTasks(string $type): void
    {
        $finder = new Finder();
        $finder->in(BP . '/src/Orba/Magento2Codegen/Test/Integration/' . $type . 'Task');
        (new TasksExecutor())->execute($finder);
    }

    private function removeGeneratedFiles(): void
    {
        exec('rm -rf ' . $_ENV['MAGENTO_ROOT_DIR'] . 'app/code/' . self::TEST_VENDOR_NAME);
    }

    private static function runMagentoSetupUpgrade(): void
    {
        exec($_ENV['MAGENTO_CONSOLE_COMMAND_PATH'] . ' setup:upgrade');
    }
}