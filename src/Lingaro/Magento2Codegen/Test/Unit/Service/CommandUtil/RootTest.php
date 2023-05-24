<?php

/**
 * Copyright © 2023 Lingaro sp. z o.o. All rights reserved.
 * See LICENSE for license details.
 */

declare(strict_types=1);

namespace Orba\Magento2Codegen\Test\Unit\Service\CommandUtil;

use Orba\Magento2Codegen\Service\CommandUtil\Root;
use Orba\Magento2Codegen\Service\CommandUtil\Template;
use Orba\Magento2Codegen\Service\Magento\Detector;
use Orba\Magento2Codegen\Test\Unit\TestCase;
use PHPUnit\Framework\MockObject\MockObject;

class RootTest extends TestCase
{
    private Root $commandUtil;

    public function setUp(): void
    {
        /** @var MockObject|Template $templateCommandUtilMock */
        $templateCommandUtilMock = $this->getMockBuilder(Template::class)
            ->disableOriginalConstructor()->getMock();
        /** @var MockObject|Detector $detectorMock */
        $detectorMock = $this->getMockBuilder(Detector::class)->disableOriginalConstructor()->getMock();
        $this->commandUtil = new Root($templateCommandUtilMock, $detectorMock);
    }

    public function testIsCurrentDirMagentoRootReturnsBoolean(): void
    {
        $result = $this->commandUtil->isCurrentDirMagentoRoot();
        $this->assertIsBool($result);
    }
}
