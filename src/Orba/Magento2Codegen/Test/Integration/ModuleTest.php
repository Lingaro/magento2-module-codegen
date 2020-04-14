<?php

namespace Orba\Magento2Codegen\Test\Integration;

use Orba\Magento2Codegen\Test\Integration\BeforeTask\Module;

/**
 * @see Module
 */
class ModuleTest extends TestCase
{
    public function testGeneratedModuleIsVisibleByMagento(): void
    {
        $output = $this->execMagentoCommand('module:status ' . TestExtension::TEST_VENDOR_NAME . '_EmptyModule');
        $this->assertStringContainsString('Module is enabled', $output[0]);
    }
}