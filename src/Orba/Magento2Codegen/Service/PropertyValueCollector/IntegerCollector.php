<?php

namespace Orba\Magento2Codegen\Service\PropertyValueCollector;

use InvalidArgumentException;
use Orba\Magento2Codegen\Model\InputPropertyInterface;
use Orba\Magento2Codegen\Model\PropertyInterface;
use Orba\Magento2Codegen\Model\IntegerProperty;
use Orba\Magento2Codegen\Util\PropertyBag;
use Symfony\Component\Console\Exception\InvalidArgumentException as ConsoleInvalidArgumentException;
use Symfony\Component\Console\Question\Question;

class IntegerCollector extends AbstractInputCollector
{
    protected function validateProperty(PropertyInterface $property): void
    {
        if (!$property instanceof IntegerProperty) {
            throw new InvalidArgumentException('Invalid property type.');
        }
    }

    protected function collectValueFromInput(InputPropertyInterface $property, PropertyBag $propertyBag)
    {
        $question = new Question($this->questionPrefix . $property->getName(), $property->getDefaultValue());

        if ($property->getRequired()) {
            $question->setValidator(function ($answer) {
                if (empty($answer)) {
                    throw new ConsoleInvalidArgumentException('Value cannot be empty.');
                }

                if (!is_int($answer) && preg_match('/^-?\d+$/', $answer) !== 1) {
                    throw new ConsoleInvalidArgumentException('Value must be integer.');
                }

                return $answer;
            });
        } else {
            $question->setValidator(function ($answer) {
                if (empty($answer)) {
                    return $answer;
                }

                if (!is_int($answer) && preg_match('/^-?\d+$/', $answer) !== 1) {
                    throw new ConsoleInvalidArgumentException('Value must be integer.');
                }

                return $answer;
            });
        }

        return $this->io->getInstance()->askQuestion($question);
    }
}
