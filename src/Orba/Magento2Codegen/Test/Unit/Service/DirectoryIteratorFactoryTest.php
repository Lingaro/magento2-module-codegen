<?php

namespace Orba\Magento2Codegen\Test\Unit\Service;

use Orba\Magento2Codegen\Service\DirectoryIteratorFactory;
use Orba\Magento2Codegen\Service\TemplateDir;
use Orba\Magento2Codegen\Test\Unit\TestCase;
use UnexpectedValueException;
use RecursiveDirectoryIterator;


class DirectoryIteratorFactoryTest extends TestCase
{
    /**
     * @var TemplateDir
     */
    private $directoryIteratorFactory;

    public function setUp(): void
    {
        $this->directoryIteratorFactory = new DirectoryIteratorFactory();
    }

    public function testIteratorThrowExceptionForNonExistingDirectory(): void
    {
        $path = $this->getPath(BP, 'non/existing/path');
        $this->expectException(UnexpectedValueException::class);
        $this->expectExceptionMessage("Directory not found: " . $path);
        $this->directoryIteratorFactory->create($path);
    }

    public function testIteratorNotThrowExceptionForExistingDirectory(): void
    {
        $path = $this->getPath(BP, 'extra/templates/source1');
        $iterator = $this->directoryIteratorFactory->create($path);
        $this->assertInstanceOf(RecursiveDirectoryIterator::class, $iterator);
    }

    /**
     * @param string $root
     * @param string $path
     * @return string
     */
    private function getPath(string $root, string $path): string
    {
        $root = rtrim($root, '/');
        $path = ltrim($path, '/');
        return $root . '/' . $path;
    }
}