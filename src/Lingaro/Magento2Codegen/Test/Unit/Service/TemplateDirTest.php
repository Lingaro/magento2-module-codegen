<?php

/**
 * Copyright © 2023 Lingaro sp. z o.o. All rights reserved.
 * See LICENSE for license details.
 */

declare(strict_types=1);

namespace Lingaro\Magento2Codegen\Test\Unit\Service;

use Lingaro\Magento2Codegen\Service\Config;
use Lingaro\Magento2Codegen\Service\DirectoryIteratorFactory;
use Lingaro\Magento2Codegen\Service\TemplateDir;
use Lingaro\Magento2Codegen\Test\Unit\TestCase;
use Symfony\Component\Config\Definition\Processor;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Yaml\Parser;

class TemplateDirTest extends TestCase
{
    private TemplateDir $templateDir;

    public function setUp(): void
    {
        $this->templateDir = new TemplateDir(
            new Config(new Processor(), new Parser()),
            new DirectoryIteratorFactory(),
            new Filesystem()
        );
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

    public function testGetPathForNewTemplate(): void
    {
        $result = $this->templateDir->getPath('templateNew');
        $this->assertDirectoryExists($result);
        $this->assertStringContainsString(BP, $result);
        $this->assertStringContainsString('templateNew', $result);
    }

    public function testGetPathForOverwrittenCoreTemplate(): void
    {
        // check new directory
        $result = $this->templateDir->getPath('templateOverwrite1');
        $this->assertDirectoryExists($result);
        $this->assertStringContainsString(BP, $result);
        $this->assertStringContainsString('extra/templates/source1', $result);
        // check core directory
        $result = str_replace('extra/templates/source1', 'templates', $result);
        $this->assertDirectoryExists($result);
    }

    public function testGetPathForOverwrittenExtraTemplate(): void
    {
        // check new directory
        $result = $this->templateDir->getPath('templateOverwrite2');
        $this->assertDirectoryExists($result);
        $this->assertStringContainsString(BP, $result);
        $this->assertStringContainsString('extra/templates/source2', $result);
        // check for original directory
        $result = str_replace('source2', 'source1', $result);
        $this->assertDirectoryExists($result);
    }
}
