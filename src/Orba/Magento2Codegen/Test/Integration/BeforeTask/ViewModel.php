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
        $layoutSource = '<?xml version="1.0" encoding="UTF-8"?>
<page xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xsi:noNamespaceSchemaLocation="urn:magento:framework:View/Layout/etc/page_configuration.xsd">
    <body>
        <referenceContainer name="content">
             <block name="' . strtolower(TestExtension::TEST_VENDOR_NAME) . '.test.view.model"
                    template="' . TestExtension::TEST_VENDOR_NAME . '_ViewModel::test.phtml">
                 <arguments>
                      <argument name="view_model" xsi:type="object">' . TestExtension::TEST_VENDOR_NAME . '\ViewModel\ViewModel\Test</argument>
                 </arguments>
             </block>
        </referenceContainer>
    </body>
</page>';
        mkdir($layoutDir);
        file_put_contents($layoutPath, $layoutSource);
    }
}