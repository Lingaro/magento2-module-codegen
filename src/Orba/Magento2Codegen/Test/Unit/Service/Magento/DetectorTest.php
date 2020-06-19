<?php

namespace Orba\Magento2Codegen\Test\Unit\Service\Magento;

use Orba\Magento2Codegen\Service\FilepathUtil;
use Orba\Magento2Codegen\Service\Magento\Detector;
use Orba\Magento2Codegen\Test\Unit\TestCase;
use PHPUnit\Framework\MockObject\MockObject;
use Symfony\Component\Filesystem\Filesystem;

class DetectorTest extends TestCase
{
    /**
     * @var Detector
     */
    private $detector;

    /**
     * @var MockObject|FilepathUtil
     */
    private $filepathUtilMock;

    /**
     * @var MockObject|Filesystem
     */
    private $filesystemMock;

    public function setUp(): void
    {
        $this->filesystemMock = $this->getMockBuilder(Filesystem::class)
            ->disableOriginalConstructor()->getMock();
        $this->filepathUtilMock = $this->getMockBuilder(FilepathUtil::class)
            ->disableOriginalConstructor()->getMock();
        $this->detector = new Detector(
            $this->filesystemMock,
            $this->filepathUtilMock
        );
    }

    public function testModuleExistsInDirReturnsFalseIfModuleConfigFileDoesntExist(): void
    {
        $this->filesystemMock->expects($this->once())->method('exists')->willReturn(false);
        $result = $this->detector->moduleExistsInDir('dir');
        $this->assertFalse($result);
    }

    public function testModuleExistsInDirReturnsTrueIfModuleConfigFileExist(): void
    {
        $this->filesystemMock->expects($this->once())->method('exists')->willReturn(true);
        $result = $this->detector->moduleExistsInDir('dir');
        $this->assertTrue($result);
    }

    public function testCoreIndexPhpExistsInDirReturnsFalseIfFileDoesntExist(): void
    {
        $this->filesystemMock->expects($this->once())->method('exists')->willReturn(false);
        $result = $this->detector->coreIndexPhpExistsInDir('dir');
        $this->assertFalse($result);
    }

    public function testCoreIndexPhpExistsInDirReturnsFalseIfFileExistsButWithInvalidContent(): void
    {
        $this->filesystemMock->expects($this->once())->method('exists')->willReturn(true);
        $this->filepathUtilMock->expects($this->once())->method('getContent')
            ->willReturn('not a root index.php content');
        $result = $this->detector->coreIndexPhpExistsInDir('dir');
        $this->assertFalse($result);
    }

    public function testCoreIndexPhpExistsInDirReturnsTrueIfFileExistsWithProperContent(): void
    {
        $content = <<<PHP
<?php
try {
    require __DIR__ . '/app/bootstrap.php';
} catch (\Exception \$e) {
    //...
}

\$bootstrap = \Magento\Framework\App\Bootstrap::create(BP, \$_SERVER);
/** @var \Magento\Framework\App\Http \$app */
\$app = \$bootstrap->createApplication(\Magento\Framework\App\Http::class);
\$bootstrap->run(\$app);
PHP;

        $this->filesystemMock->expects($this->once())->method('exists')->willReturn(true);
        $this->filepathUtilMock->expects($this->once())->method('getContent')->willReturn($content);
        $result = $this->detector->coreIndexPhpExistsInDir('dir');
        $this->assertTrue($result);
    }
}
