<?php

namespace Orba\Magento2Codegen\Service\PropertyValueCollector;

use InvalidArgumentException;
use Orba\Magento2Codegen\Model\ChoiceProperty;
use Orba\Magento2Codegen\Model\PropertyInterface;
use Orba\Magento2Codegen\Util\PropertyBag;
use Symfony\Component\Console\Question\ChoiceQuestion;

class ChoiceCollector extends AbstractInputCollector
{
    protected function validateProperty(PropertyInterface $property): void
    {
        if (!$property instanceof ChoiceProperty) {
            throw new InvalidArgumentException('Invalid property type.');
        }
    }

    protected function collectValueFromInput(PropertyInterface $property, PropertyBag $propertyBag)
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
