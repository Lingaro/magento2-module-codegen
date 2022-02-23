<?php

/**
 * @copyright Copyright © 2022 Orba. All rights reserved.
 * @author    info@orba.co
 */

declare(strict_types=1);

namespace Orba\Magento2Codegen\Test\Unit\Service\PropertyValueCollector;

use InvalidArgumentException;
use Orba\Magento2Codegen\Model\ConstProperty;
use Orba\Magento2Codegen\Model\PropertyInterface;
use Orba\Magento2Codegen\Service\PropertyValueCollector\CollectorFactory;
use Orba\Magento2Codegen\Service\PropertyValueCollector\ConstCollector;
use Orba\Magento2Codegen\Test\Unit\TestCase;

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
