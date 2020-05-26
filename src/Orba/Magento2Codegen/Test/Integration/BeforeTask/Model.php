<?php

namespace Orba\Magento2Codegen\Test\Integration\BeforeTask;

use Orba\Magento2Codegen\Service\TaskInterface;
use Orba\Magento2Codegen\Test\Integration\CommandTesterRunner;
use Orba\Magento2Codegen\Test\Integration\TestExtension;

class Model implements TaskInterface
{
    public function execute(): void
    {
        CommandTesterRunner::run(
            'module',
            'Model',
            [TestExtension::TEST_VENDOR_NAME, 'Model']
        );
        // Arguments: entityName, fields.0.name, fields.0.databaseType, fields.0.length, fields.0.nullable,
        //            fields.0.unsigned, fields.0.precision, fields.0.scale, another item?, fields.0.name,
        //            fields.0.databaseType, fields.0.length, fields.0.nullable, fields.0.unsigned, fields.0.precision,
        //            fields.0.scale, another item?
        CommandTesterRunner::run(
            'model',
            'Model',
            ['llama', 'name', 'varchar', '32', '0', '', '', '', 'yes', 'points', 'decimal', '', '1', '1', '6', '3', 'no']
        );

        $patchDir = $_ENV['MAGENTO_ROOT_DIR'] . 'app/code/' . TestExtension::TEST_VENDOR_NAME
            . '/Model/Setup/Patch/Data';
        $patchPath = $patchDir . '/PlayWithLlamas.php';
        $patchSource = file_get_contents(
            BP . '/src/Orba/Magento2Codegen/Test/Integration/_files/Model/Setup/Patch/Data/PlayWithLlamas.php'
        );
        mkdir($patchDir, 0777, true);
        file_put_contents($patchPath, $patchSource);
    }
}
