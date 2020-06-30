<?php

namespace Orba\Magento2Codegen\Test\Unit\Service\CommandUtil;

use Orba\Magento2Codegen\Service\CommandUtil\Root;
use Orba\Magento2Codegen\Service\CommandUtil\Template;
use Orba\Magento2Codegen\Service\Magento\Detector;
use Orba\Magento2Codegen\Test\Unit\TestCase;
use PHPUnit\Framework\MockObject\MockObject;

class RootTest extends TestCase
{
    /**
     * @var Root
     */
    private $commandUtil;

    public function setUp(): void
    {
        /** @var MockObject|Template $templateCommandUtilMock */
        $templateCommandUtilMock = $this->getMockBuilder(Template::class)
            ->disableOriginalConstructor()->getMock();
        /** @var MockObject|Detector $detectorMock */
        $detectorMock = $this->getMockBuilder(Detector::class)->disableOriginalConstructor()->getMock();
        $this->commandUtil = new Root($templateCommandUtilMock, $detectorMock);
    }

    public function testIsCurrentDirMagentoRootReturnsBoolean()
    {
        $result = $this->commandUtil->isCurrentDirMagentoRoot();
        $this->assertIsBool($result);
    }
}
