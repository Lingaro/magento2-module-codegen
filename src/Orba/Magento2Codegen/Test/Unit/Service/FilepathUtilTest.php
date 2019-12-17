<?php

namespace Orba\Magento2Codegen\Test\Unit\Service;

use InvalidArgumentException;
use Orba\Magento2Codegen\Service\FilepathUtil;
use Orba\Magento2Codegen\Service\TemplateDir;
use PHPUnit\Framework\TestCase;

class FilepathUtilTest extends TestCase
{
    /**
     * @var FilepathUtil
     */
    private $filepathUtil;

    public function setUp(): void
    {
        $this->filepathUtil = new FilepathUtil();
    }

    public function testGetAbsolutePathThrowsExceptionIfFilepathIsEmpty()
    {
        $this->expectException(InvalidArgumentException::class);
        $this->filepathUtil->getAbsolutePath('', null);
    }

    public function testGetAbsolutePathThrowsExceptionIfFilepathStartsWithSlash()
    {
        $this->expectException(InvalidArgumentException::class);
        $this->filepathUtil->getAbsolutePath('/path/file.php', null);
    }

    public function testGetAbsolutePathThrowsExceptionIfRootDirDoesNotStartWithSlash()
    {
        $this->expectException(InvalidArgumentException::class);
        $this->filepathUtil->getAbsolutePath('path/file.php', 'root/dir');
    }

    public function testGetAbsolutePathReturnsPathWithCwdIfRootDirIsNotSpecified()
    {
        $result = $this->filepathUtil->getAbsolutePath('path/file.php', null);
        $this->assertSame(getcwd() . '/path/file.php', $result);
    }

    public function testGetAbsolutePathReturnsConcatanatedPathIfRootDirIsSpecified()
    {
        $result = $this->filepathUtil->getAbsolutePath('path/file.php', '/root/dir');
        $this->assertSame('/root/dir/path/file.php', $result);
    }

    public function testRemoveTemplateDirFromPathThrowsExceptionIfFilepathIsEmpty()
    {
        $this->expectException(InvalidArgumentException::class);
        $this->filepathUtil->removeTemplateDirFromPath('');
    }

    public function testRemoveTemplateDirFromPathThrowsExceptionIfFilepathDoesNotStartWithTemplateDir()
    {
        $this->expectException(InvalidArgumentException::class);
        $this->filepathUtil->removeTemplateDirFromPath('/somedir/module/file.php');
    }

    public function testRemoveTemplateDirFromPathThrowsExceptionIfFilepathDoesNotContainTemplateSubDir()
    {
        $this->expectException(InvalidArgumentException::class);
        $this->filepathUtil->removeTemplateDirFromPath(TemplateDir::DIR . '/file.php');
    }

    public function testRemoveTemplateDirFromPathReturnsPathWithRemovedTemplateDirIfFilepathIsCorrect()
    {
        $result = $this->filepathUtil->removeTemplateDirFromPath(TemplateDir::DIR . '/module/some/file.php');
        $this->assertSame('some/file.php', $result);
    }
}