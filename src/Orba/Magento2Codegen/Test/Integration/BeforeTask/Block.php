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
        $layoutSource = '<?xml version="1.0" encoding="UTF-8"?>
<page xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xsi:noNamespaceSchemaLocation="urn:magento:framework:View/Layout/etc/page_configuration.xsd">
    <body>
        <referenceContainer name="content">
             <block class="' . TestExtension::TEST_VENDOR_NAME . '\Block\Block\Test"
               name="' . strtolower(TestExtension::TEST_VENDOR_NAME) . '.test.block"
               template="' . TestExtension::TEST_VENDOR_NAME . '_Block::test.phtml"/>
        </referenceContainer>
    </body>
</page>';
        mkdir($layoutDir);
        file_put_contents($layoutPath, $layoutSource);
    }
}