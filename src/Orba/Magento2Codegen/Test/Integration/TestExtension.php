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
        $this->cleanMagentoDatabase();
        $this->executeTasks('Before');
        $this->runMagentoSetupUpgrade();
    }

    public function executeAfterLastTest(): void
    {
        $this->executeTasks('After');
        $this->removeGeneratedFiles();
        $this->runMagentoSetupUpgrade();
        $this->cleanMagentoDatabase();
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

    private function runMagentoSetupUpgrade(): void
    {
        exec($_ENV['MAGENTO_CONSOLE_COMMAND_PATH'] . ' setup:upgrade');
    }

    private function cleanMagentoDatabase()
    {
        MysqlConnection::getInstance()->exec('SET FOREIGN_KEY_CHECKS = 0');
        $tables = MysqlConnection::getInstance()->query('SHOW TABLES')->fetchAll();
        foreach ($tables as $table) {
            if (strstr($table[0], 'codegen_')) {
                MysqlConnection::getInstance()->exec('DROP TABLE ' . $table[0]);
            }
        }
        MysqlConnection::getInstance()->exec('DELETE FROM patch_list WHERE patch_name LIKE "Codegen%"');
        MysqlConnection::getInstance()->exec('SET FOREIGN_KEY_CHECKS = 1');
    }
}