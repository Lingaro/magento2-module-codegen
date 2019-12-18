<?php

namespace Orba\Magento2Codegen\Test\Unit\Service\CommandUtil;

use Orba\Magento2Codegen\Service\CommandUtil\Module;
use Orba\Magento2Codegen\Service\FilepathUtil;
use Orba\Magento2Codegen\Service\TemplatePropertyBagFactory;
use Orba\Magento2Codegen\Test\Unit\TestCase;
use Orba\Magento2Codegen\Util\TemplatePropertyBag;
use PHPUnit\Framework\MockObject\MockObject;
use Symfony\Component\Filesystem\Filesystem;

class ModuleTest extends TestCase
{
    /**
     * @var Module
     */
    private $module;

    /**
     * @var MockObject|FilepathUtil
     */
    private $filepathUtilMock;

    /**
     * @var MockObject|Filesystem
     */
    private $filesystemMock;

    /**
     * @var MockObject|TemplatePropertyBagFactory
     */
    private $propertyBagFactoryMock;

    public function setUp(): void
    {
        $this->filepathUtilMock = $this->getMockBuilder(FilepathUtil::class)
            ->disableOriginalConstructor()->getMock();
        $this->filesystemMock = $this->getMockBuilder(Filesystem::class)
            ->disableOriginalConstructor()->getMock();
        $this->propertyBagFactoryMock = $this->getMockBuilder(TemplatePropertyBagFactory::class)
            ->disableOriginalConstructor()->getMock();
        $this->module = new Module(
            $this->filepathUtilMock,
            $this->filesystemMock,
            $this->propertyBagFactoryMock
        );
    }

    public function testExistsReturnsFalseIfRegistrationFileDoesntExist(): void
    {
        $this->filesystemMock->expects($this->once())->method('exists')->willReturn(false);
        $result = $this->module->exists('rootDir');
        $this->assertFalse($result);
    }

    public function testExistsReturnsTrueIfRegistrationFileExist(): void
    {
        $this->filesystemMock->expects($this->once())->method('exists')->willReturn(true);
        $result = $this->module->exists('rootDir');
        $this->assertTrue($result);
    }

    public function testGetPropertyBagReturnsEmptyBagIfModuleDataNotFoundInRegistrationFile(): void
    {
        $this->filepathUtilMock->expects($this->once())->method('getContent')
            ->willReturn('broken file with not module data');
        $result = $this->module->getPropertyBag('rootDir');
        $this->assertFalse(isset($result['vendorname']));
        $this->assertFalse(isset($result['modulename']));
    }

    public function testGetPropertyBagReturnsProperlyFilledBagIfModuleDataFoundInRegistrationFile(): void
    {
        $registrationFileContent = <<<PHP
<?php
\Magento\Framework\Component\ComponentRegistrar::register(
    \Magento\Framework\Component\ComponentRegistrar::MODULE,
    'Orba_Test',
    __DIR__
);
PHP;
        $this->filepathUtilMock->expects($this->once())->method('getContent')
            ->willReturn($registrationFileContent);
        $this->propertyBagFactoryMock->expects($this->once())->method('create')
            ->willReturn(new TemplatePropertyBag());
        $result = $this->module->getPropertyBag('rootDir');
        $this->assertSame('orba', $result['vendorname']);
        $this->assertSame('test', $result['modulename']);
    }
}