<?php

namespace Orba\Magento2Codegen\Test\Integration;

use Orba\Magento2Codegen\Application;
use Orba\Magento2Codegen\Kernel;
use Orba\Magento2Codegen\Service\IO;
use Orba\Magento2Codegen\Util\Symfony\CommandTester;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Output\StreamOutput;

class CommandTesterRunner
{
    public static function run(string $templateName, string $moduleName, array $inputs): void
    {
        $kernel = new Kernel('dev', true);
        $kernel->boot();

        $container = $kernel->getContainer();
        /** @var Application $application */
        $application = $container->get(Application::class);
        $command = $application->find('template:generate');
        /** @var IO $io */
        $io = $container->get(IO::class);
        $input = new ArrayInput([
            'template' => $templateName,
            '--root-dir' => $_ENV['MAGENTO_ROOT_DIR'] . 'app/code/' . TestExtension::TEST_VENDOR_NAME . '/' . $moduleName
        ]);
        $output = new StreamOutput(fopen('php://memory', 'w', false));
        $io->init($input, $output);
        $commandTester = new CommandTester($command, $input, $output);
        $commandTester->setInputs($inputs);
        $commandTester->execute([]);
    }
}