<?php

namespace Orba\Magento2Codegen\Test\Unit\Service;

use InvalidArgumentException;
use Orba\Magento2Codegen\Service\FilepathUtil;
use Orba\Magento2Codegen\Service\FinderFactory;
use Orba\Magento2Codegen\Service\TemplateDir;
use Orba\Magento2Codegen\Test\Unit\TestCase;

class FilepathUtilTest extends TestCase
{
    /**
     * @var FilepathUtil
     */
    private $filepathUtil;

    public function setUp(): void
    {
        $this->filepathUtil = new FilepathUtil(new FinderFactory());
    }

    public function testGetAbsolutePathThrowsExceptionIfFilepathIsEmpty(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->filepathUtil->getAbsolutePath('', '/root/dir');
    }

    public function testGetAbsolutePathThrowsExceptionIfRootDirIsEmpty(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->filepathUtil->getAbsolutePath('path/file.php', '');
    }

    public function testGetAbsolutePathThrowsExceptionIfFilepathStartsWithSlash(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->filepathUtil->getAbsolutePath('/path/file.php', '/root/dir');
    }

    public function testGetAbsolutePathThrowsExceptionIfRootDirDoesNotStartWithSlash(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->filepathUtil->getAbsolutePath('path/file.php', 'root/dir');
    }

    public function testGetAbsolutePathReturnsConcatanatedPathIfRootDirIsSpecified(): void
    {
        $result = $this->filepathUtil->getAbsolutePath('path/file.php', '/root/dir');
        $this->assertSame('/root/dir/path/file.php', $result);
    }

    public function testRemoveTemplateDirFromPathThrowsExceptionIfFilepathIsEmpty(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->filepathUtil->removeTemplateDirFromPath('');
    }

    public function testRemoveTemplateDirFromPathThrowsExceptionIfFilepathDoesNotStartWithTemplateDir(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->filepathUtil->removeTemplateDirFromPath('/somedir/module/file.php');
    }

    public function testRemoveTemplateDirFromPathThrowsExceptionIfFilepathDoesNotContainTemplateSubDir(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->filepathUtil->removeTemplateDirFromPath(TemplateDir::DIR . '/file.php');
    }

    public function testRemoveTemplateDirFromPathReturnsPathWithRemovedTemplateDirIfFilepathIsCorrect(): void
    {
        $result = $this->filepathUtil->removeTemplateDirFromPath(TemplateDir::DIR . '/module/some/file.php');
        $this->assertSame('some/file.php', $result);
    }

    public function testGetFileNameReturnsFileNameFromRelativePath(): void
    {
        $result = $this->filepathUtil->getFileName('foo/bar.xml');
        $this->assertSame('bar.xml', $result);
    }

    public function testGetFileNameReturnsFileNameFromAbsolutePath(): void
    {
        $result = $this->filepathUtil->getFileName('/foo/bar.xml');
        $this->assertSame('bar.xml', $result);
    }

    public function testGetFileNameThrowsExceptionIfThereIsNoFileNameInSpecifiedString(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->filepathUtil->getFileName('/');
    }

    public function testGetContentReturnsEmptyStringIfFileDoesNotExist(): void
    {
        $result = $this->filepathUtil->getContent(BP . '/nonexistent.file');
        $this->assertSame('', $result);
    }

    public function testGetContentReturnsFileContentIfFileExist(): void
    {
        $result = $this->filepathUtil->getContent(BP . '/templates/example/foo.xml');
        $this->assertSame('<foo></foo>', $result);
    }
}