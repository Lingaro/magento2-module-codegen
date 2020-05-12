<?php

namespace Orba\Magento2Codegen\Test\Integration\BeforeTask;

use Orba\Magento2Codegen\Service\TaskInterface;
use Orba\Magento2Codegen\Test\Integration\CommandTesterRunner;
use Orba\Magento2Codegen\Test\Integration\TestExtension;

class ViewModel implements TaskInterface
{
    public function execute(): void
    {
        CommandTesterRunner::run(
            'module',
            'ViewModel',
            [TestExtension::TEST_VENDOR_NAME, 'ViewModel']
        );
        // Arguments: viewModelName
        CommandTesterRunner::run(
            'viewModel',
            'ViewModel',
            ['test']
        );

        $viewModelPath = $_ENV['MAGENTO_ROOT_DIR'] . 'app/code/' . TestExtension::TEST_VENDOR_NAME
            . '/ViewModel/ViewModel/Test.php';
        $viewModelSource = file_get_contents($viewModelPath);
        $viewModelSource = str_replace(
            '// Write your methods here...',
            'public function getFoo(): string { return "bar"; }',
            $viewModelSource
        );
        file_put_contents($viewModelPath, $viewModelSource);

        $templatePath = $_ENV['MAGENTO_ROOT_DIR'] . 'app/code/' . TestExtension::TEST_VENDOR_NAME
            . '/ViewModel/view/frontend/templates/test.phtml';
        $templateSource = file_get_contents($templatePath);
        $templateSource = str_replace(
            '// Add your template code here. Use $viewModel for getting your data.',
            'echo "<p id=\"test-view-model\">" . $viewModel->getFoo() . "</p>"',
            $templateSource
        );
        file_put_contents($templatePath, $templateSource);

        $layoutDir = $_ENV['MAGENTO_ROOT_DIR'] . 'app/code/' . TestExtension::TEST_VENDOR_NAME
            . '/ViewModel/view/frontend/layout';
        $layoutPath = $layoutDir . '/default.xml';
        $layoutSource = file_get_contents(
            BP . '/src/Orba/Magento2Codegen/Test/Integration/_files/ViewModel/view/frontend/layout/default.xml'
        );
        mkdir($layoutDir, 0777, true);
        file_put_contents($layoutPath, $layoutSource);
    }
}