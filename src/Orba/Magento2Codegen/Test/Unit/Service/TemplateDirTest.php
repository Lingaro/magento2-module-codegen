<?php

namespace Orba\Magento2Codegen\Test\Unit\Service;

use Orba\Magento2Codegen\Service\TemplateDir;
use Orba\Magento2Codegen\Test\Unit\TestCase;
use Symfony\Component\Filesystem\Filesystem;

class TemplateDirTest extends TestCase
{
    /**
     * @var TemplateDir
     */
    private $templateDir;

    public function setUp(): void
    {
        $this->templateDir = new TemplateDir(new Filesystem());
    }

    public function testGetPathReturnsNullIfTemplateDoesNotExist(): void
    {
        $result = $this->templateDir->getPath('nonexistent');
        $this->assertNull($result);
    }

    public function testGetPathReturnsFullPathIfTemplateExists(): void
    {
        $result = $this->templateDir->getPath('example');
        $this->assertSame(TemplateDir::DIR . '/example', $result);
    }
}