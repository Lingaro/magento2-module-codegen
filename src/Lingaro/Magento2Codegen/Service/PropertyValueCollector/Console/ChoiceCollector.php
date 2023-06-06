<?php

/**
 * Copyright © 2023 Lingaro sp. z o.o. All rights reserved.
 * See LICENSE for license details.
 */

declare(strict_types=1);

namespace Lingaro\Magento2Codegen\Service\PropertyValueCollector\Console;

use InvalidArgumentException;
use Lingaro\Magento2Codegen\Model\ChoiceProperty;
use Lingaro\Magento2Codegen\Model\InputPropertyInterface;
use Lingaro\Magento2Codegen\Model\PropertyInterface;
use Lingaro\Magento2Codegen\Util\PropertyBag;
use Symfony\Component\Console\Question\ChoiceQuestion;

class ChoiceCollector extends AbstractConsoleCollector
{
    protected string $propertyType = ChoiceProperty::class;

    protected function collectValueFromInput(InputPropertyInterface $property, PropertyBag $propertyBag)
    {
        /** @var ChoiceProperty $property */
        $question = new ChoiceQuestion(
            $this->questionPrefix . $property->getName(),
            $property->getOptions(),
            $property->getDefaultValue()
        );
        $question->setErrorMessage('You have to choose one of the available options.');
        return $this->io->getInstance()->askQuestion($question);
    }
}
