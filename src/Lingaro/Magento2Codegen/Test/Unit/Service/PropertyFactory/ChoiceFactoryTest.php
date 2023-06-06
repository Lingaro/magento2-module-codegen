<?php

/**
 * Copyright © 2023 Lingaro sp. z o.o. All rights reserved.
 * See LICENSE for license details.
 */

declare(strict_types=1);

namespace Lingaro\Magento2Codegen\Test\Unit\Service\PropertyFactory;

use Lingaro\Magento2Codegen\Model\ChoiceProperty;
use Lingaro\Magento2Codegen\Service\PropertyBuilder;
use Lingaro\Magento2Codegen\Service\PropertyFactory\ChoiceFactory;
use Lingaro\Magento2Codegen\Service\StringValidator;
use Lingaro\Magento2Codegen\Test\Unit\TestCase;

class ChoiceFactoryTest extends TestCase
{
    private ChoiceFactory $choiceFactory;

    public function setUp(): void
    {
        $this->choiceFactory = new ChoiceFactory(new PropertyBuilder());
    }

    public function testCreateReturnsChoiceProperty(): void
    {
        $result = $this->choiceFactory->create('name', ['options' => ['foo', 'bar']]);
        $this->assertInstanceOf(ChoiceProperty::class, $result);
    }
}
