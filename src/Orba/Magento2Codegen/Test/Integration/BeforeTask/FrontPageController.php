<?php

namespace Orba\Magento2Codegen\Test\Integration\BeforeTask;

use Orba\Magento2Codegen\Service\TaskInterface;
use Orba\Magento2Codegen\Test\Integration\CommandTesterRunner;
use Orba\Magento2Codegen\Test\Integration\TestExtension;

class FrontPageController implements TaskInterface
{
    public function execute(): void
    {
        CommandTesterRunner::run(
            'module',
            'FrontPageController',
            [TestExtension::TEST_VENDOR_NAME, 'FrontPageController']
        );
        // Arguments: frontName, controllerName, actionName, pageLayout, viewModelName
        CommandTesterRunner::run(
            'frontPageController',
            'FrontPageController',
            ['one_two', 'three_four', 'five_six', '1column', 'bar']
        );

        $viewModelPath = $_ENV['MAGENTO_ROOT_DIR'] . 'app/code/' . TestExtension::TEST_VENDOR_NAME
            . '/FrontPageController/ViewModel/Bar.php';
        $viewModelSource = file_get_contents($viewModelPath);
        $viewModelSource = str_replace(
            '// Write your methods here...',
            'public function getFoo(): string { return "bar"; }',
            $viewModelSource
        );
        file_put_contents($viewModelPath, $viewModelSource);

        $templatePath = $_ENV['MAGENTO_ROOT_DIR'] . 'app/code/' . TestExtension::TEST_VENDOR_NAME
            . '/FrontPageController/view/frontend/templates/bar.phtml';
        $templateSource = file_get_contents($templatePath);
        $templateSource = str_replace(
            '// Add your template code here. Use $viewModel for getting your data.',
            'echo "<p id=\"front-page-test\">" . $viewModel->getFoo() . "</p>"',
            $templateSource
        );
        file_put_contents($templatePath, $templateSource);
    }
}