<?php

namespace Orba\Magento2Codegen\Test\Unit\Service;

use InvalidArgumentException;
use Orba\Magento2Codegen\Service\FinderFactory;
use Orba\Magento2Codegen\Service\TemplateDir;
use Orba\Magento2Codegen\Service\TemplateFile;
use Orba\Magento2Codegen\Service\TemplateProcessorInterface;
use Orba\Magento2Codegen\Test\Unit\TestCase;
use PHPUnit\Framework\MockObject\MockObject;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Finder\SplFileInfo;
use Symfony\Component\Yaml\Parser;

class TemplateFileTest extends TestCase
{
    /**
     * @var TemplateFile
     */
    private $templateFile;

    /**
     * @var MockObject|TemplateProcessorInterface
     */
    private $templateProcessorMock;

    public function setUp(): void
    {
        $this->templateProcessorMock = $this->getMockBuilder(TemplateProcessorInterface::class)
            ->getMockForAbstractClass();
        $this->templateFile = new TemplateFile(
            new TemplateDir(new Filesystem()),
            new FinderFactory(),
            new Parser(),
            $this->templateProcessorMock
        );
    }

    public function testExistsReturnsFalseIfTemplateDoesNotExist(): void
    {
        $result = $this->templateFile->exists('nonexistent');
        $this->assertFalse($result);
    }

    public function testExistsReturnsTrueIfTemplateExists(): void
    {
        $result = $this->templateFile->exists('example');
        $this->assertTrue($result);
    }

    public function testGetDescriptionThrowsExceptionIfTemplateDoesNotExist(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->templateFile->getDescription('nonexistent');
    }

    public function testGetDescriptionReturnsEmptyStringIfConfigDirDoesNotExist(): void
    {
        $result = $this->templateFile->getDescription('noconfig');
        $this->assertSame('', $result);
    }

    public function testGetDescriptionReturnsEmptyStringIfDescriptionFileDoesNotExist(): void
    {
        $result = $this->templateFile->getDescription('emptyconfig');
        $this->assertSame('', $result);
    }

    public function testGetDescriptionReturnsDescriptionFileContentIfDescriptionFileExists(): void
    {
        $result = $this->templateFile->getDescription('example');
        $this->assertSame('Exemplary description', $result);
    }

    public function testGetDependenciesThrowsExceptionIfTemplateDoesNotExist(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->templateFile->getDependencies('nonexistent');
    }

    public function testGetDependenciesReturnsEmptyArrayIfConfigDirDoesNotExist(): void
    {
        $result = $this->templateFile->getDependencies('noconfig');
        $this->assertSame([], $result);
    }

    public function testGetDependenciesThrowsExceptionIfConfigYmlIsInvalid(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->templateFile->getDependencies('stringinconfigyml');
    }

    public function testGetDependenciesThrowsExceptionIfDependenciesArrayIsNested(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->templateFile->getDependencies('nestedarrayofdependencies');
    }

    public function testGetDependenciesThrowsExceptionIfDependencyDoesNotExist(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->templateFile->getDependencies('nonexistentdependency');
    }

    public function testGetDependenciesReturnsEmptyArrayIfDependenciesDoesNotExistInConfigYml(): void
    {
        $result = $this->templateFile->getDependencies('nodependenciesinconfigyml');
        $this->assertSame([], $result);
    }

    public function testGetDependenciesReturnsArrayOfFirstLevelDependenciesIfDependenciesExistsAndNestedArgumentIsFalse(): void
    {
        $result = $this->templateFile->getDependencies('example', false);
        $this->assertSame(['example2'], $result);
    }

    public function testGetDependenciesReturnsArrayOfAllLevelsDependenciesIfDependenciesExistsAndNestedArgumentIsTrue(): void
    {
        $result = $this->templateFile->getDependencies('example', true);
        $this->assertSame(['example2', 'emptyconfig'], $result);
    }

    public function testGetTemplateFilesReturnsEmptyArrayIfTemplatesArrayIsEmpty()
    {
        $result = $this->templateFile->getTemplateFiles([]);
        $this->assertSame([], $result);
    }

    public function testGetTemplateFilesThrowsExceptionIfOneOfTheTemplatesDoesNotExist()
    {
        $this->expectException(InvalidArgumentException::class);
        $this->templateFile->getTemplateFiles(['example', 'nonexistent']);
    }

    public function testGetTemplateFilesReturnsArrayOfNonConfigFilesIfTemplatesArrayIsNotEmptyAndAllTemplatesExist()
    {
        $result = $this->templateFile->getTemplateFiles(['example', 'example2']);
        $this->assertContainsOnlyInstancesOf(SplFileInfo::class, $result);
        $this->assertCount(2, $result);
    }
}
