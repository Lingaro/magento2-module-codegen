<?php

/**
 * Copyright © 2023 Lingaro sp. z o.o. All rights reserved.
 * See LICENSE for license details.
 */

declare(strict_types=1);

namespace Lingaro\Magento2Codegen\Test\Unit\Service\PropertyValueCollector;

use InvalidArgumentException;
use Lingaro\Magento2Codegen\Model\ConstProperty;
use Lingaro\Magento2Codegen\Model\PropertyInterface;
use Lingaro\Magento2Codegen\Service\PropertyValueCollector\CollectorFactory;
use Lingaro\Magento2Codegen\Service\PropertyValueCollector\ConstCollector;
use Lingaro\Magento2Codegen\Test\Unit\TestCase;

class CollectorFactoryTest extends TestCase
{
    public function testCreateThrowsExceptionIfCollectorClassNotMapped(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $collectorFactory = new CollectorFactory();
        $collectorFactory->create(
            'console',
            $this->getMockBuilder(PropertyInterface::class)->getMockForAbstractClass()
        );
    }

    public function testCreateReturnsCollectorIfItIsMapped(): void
    {
        $collectorFactory = new CollectorFactory([
            'console' => [
                ConstProperty::class => new ConstCollector()
            ]
        ]);
        $result = $collectorFactory->create('console', new ConstProperty());
        $this->assertInstanceOf(ConstCollector::class, $result);
    }
}
