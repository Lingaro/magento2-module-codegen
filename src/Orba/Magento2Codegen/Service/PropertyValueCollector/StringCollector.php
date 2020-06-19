<?php

namespace Orba\Magento2Codegen\Service\PropertyValueCollector;

use InvalidArgumentException;
use Orba\Magento2Codegen\Model\PropertyInterface;
use Orba\Magento2Codegen\Model\StringProperty;
use Symfony\Component\Console\Exception\InvalidArgumentException as ConsoleInvalidArgumentException;
use Symfony\Component\Console\Question\Question;

class StringCollector extends AbstractInputCollector
{
    protected function validateProperty(PropertyInterface $property): void
    {
        if (!$property instanceof StringProperty) {
            throw new InvalidArgumentException('Invalid property type.');
        }
    }

    /**
     * @param PropertyInterface|StringProperty $property
     * @return mixed
     */
    protected function collectValueFromInput(PropertyInterface $property)
    {
        $question = new Question($this->questionPrefix . $property->getName(), $property->getDefaultValue());

        if ($property->getRequired()) {
            $question->setValidator(function ($answer) {
                if (empty($answer)) {
                    throw new ConsoleInvalidArgumentException(
                        "Value must not be empty."
                    );
                }
                return $answer;
            });
        }

        return $this->io->getInstance()->askQuestion($question);
    }
}