<?php

/**
 * Copyright © 2023 Lingaro sp. z o.o. All rights reserved.
 * See LICENSE for license details.
 */

declare(strict_types=1);

namespace Lingaro\Magento2Codegen\Service\PropertyValueCollector\Console;

use Lingaro\Magento2Codegen\Model\InputPropertyInterface;
use Lingaro\Magento2Codegen\Model\PropertyInterface;
use Lingaro\Magento2Codegen\Service\IO;
use Lingaro\Magento2Codegen\Service\PropertyValueCollector\AbstractCollector;
use Lingaro\Magento2Codegen\Util\PropertyBag;
use RuntimeException;

abstract class AbstractConsoleCollector extends AbstractCollector
{
    protected IO $io;
    protected string $questionPrefix = '';

    public function __construct(IO $io)
    {
        $this->io = $io;
    }

    public function setQuestionPrefix(string $prefix): void
    {
        $this->questionPrefix = $prefix;
    }

    protected function internalCollectValue(PropertyInterface $property, PropertyBag $propertyBag)
    {
        if (!$property instanceof InputPropertyInterface) {
            throw new RuntimeException('Invalid property type.');
        }
        $blockContent = '';
        if ($property->getRequired()) {
            $blockContent .= '[<comment>REQUIRED</comment>] ';
        }
        $description = $property->getDescription();
        if ($description) {
            $blockContent .= $description;
        }
        if ($blockContent) {
            $this->io->getInstance()->writeln("\n" . ' [' . $property->getName() . '] ' . $blockContent);
        }
        return $this->collectValueFromInput($property, $propertyBag);
    }

    /**
     * @return mixed
     */
    abstract protected function collectValueFromInput(InputPropertyInterface $property, PropertyBag $propertyBag);
}
