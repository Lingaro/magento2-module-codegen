<?php

/**
 * Copyright © 2023 Lingaro sp. z o.o. All rights reserved.
 * See LICENSE for license details.
 */

declare(strict_types=1);

namespace Orba\Magento2Codegen\Service\PropertyValueCollector\Console;

use InvalidArgumentException;
use Orba\Magento2Codegen\Model\InputPropertyInterface;
use Orba\Magento2Codegen\Model\PropertyInterface;
use Orba\Magento2Codegen\Model\StringProperty;
use Orba\Magento2Codegen\Util\PropertyBag;
use Symfony\Component\Console\Exception\InvalidArgumentException as ConsoleInvalidArgumentException;
use Symfony\Component\Console\Question\Question;
use Orba\Magento2Codegen\Service\IO;
use Orba\Magento2Codegen\Service\StringValidator;

class StringCollector extends AbstractConsoleCollector
{
    private StringValidator $stringValidator;

    protected string $propertyType = StringProperty::class;

    public function __construct(IO $io, StringValidator $stringValidator)
    {
        parent::__construct($io);
        $this->stringValidator = $stringValidator;
    }

    protected function collectValueFromInput(InputPropertyInterface $property, PropertyBag $propertyBag): string
    {
        $question = new Question($this->questionPrefix . $property->getName(), $property->getDefaultValue());

        $stringValidator    = $this->stringValidator;
        $propertyValidators = $property->getValidators();
        $isRequired         = $property->getRequired();

        $question->setValidator(function ($answer) use ($stringValidator, $isRequired, $propertyValidators) {
            $answer = (string) $answer;
            if ($isRequired && $answer === '') {
                throw new ConsoleInvalidArgumentException('Value cannot be empty.');
            }
            if ($answer !== '' && $propertyValidators) {
                try {
                    $stringValidator->validate($answer, $propertyValidators);
                } catch (InvalidArgumentException $e) {
                    throw new ConsoleInvalidArgumentException($e->getMessage());
                }
            }
            return $answer;
        });

        return (string) $this->io->getInstance()->askQuestion($question);
    }
}
