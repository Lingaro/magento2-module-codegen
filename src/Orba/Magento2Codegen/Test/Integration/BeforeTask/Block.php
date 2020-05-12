<?php

namespace Orba\Magento2Codegen\Test\Integration\BeforeTask;

use Orba\Magento2Codegen\Service\TaskInterface;
use Orba\Magento2Codegen\Test\Integration\CommandTesterRunner;
use Orba\Magento2Codegen\Test\Integration\TestExtension;

class Block implements TaskInterface
{
    public function execute(): void
    {
        CommandTesterRunner::run(
            'module',
            'Block',
            [TestExtension::TEST_VENDOR_NAME, 'Block']
        );
        // Arguments: blockName
        CommandTesterRunner::run(
            'block',
            'Block',
            ['test']
        );

        $blockPath = $_ENV['MAGENTO_ROOT_DIR'] . 'app/code/' . TestExtension::TEST_VENDOR_NAME . '/Block/Block/Test.php';
        $blockSource = file_get_contents($blockPath);
        $blockSource = str_replace(
            '// Write your methods here...',
            'public function getFoo(): string { return "bar"; }',
            $blockSource
        );
        file_put_contents($blockPath, $blockSource);

        $templatePath = $_ENV['MAGENTO_ROOT_DIR'] . 'app/code/' . TestExtension::TEST_VENDOR_NAME
            . '/Block/view/frontend/templates/test.phtml';
        $templateSource = file_get_contents($templatePath);
        $templateSource = str_replace(
            '// Add your template code here...',
            'echo "<p id=\"test-block\">" . $block->getFoo() . "</p>"',
            $templateSource
        );
        file_put_contents($templatePath, $templateSource);

        $layoutDir = $_ENV['MAGENTO_ROOT_DIR'] . 'app/code/' . TestExtension::TEST_VENDOR_NAME . '/Block/view/frontend/layout';
        $layoutPath = $layoutDir . '/default.xml';
        $layoutSource = file_get_contents(
            BP . '/src/Orba/Magento2Codegen/Test/Integration/_files/Block/view/frontend/layout/default.xml'
        );
        mkdir($layoutDir, 0777, true);
        file_put_contents($layoutPath, $layoutSource);
    }
}