<?php

/**
 * @copyright Copyright © 2021 Orba. All rights reserved.
 * @author    info@orba.co
 */

declare(strict_types=1);

namespace Orba\Magento2Codegen\Service\PropertyValueCollector;

use InvalidArgumentException;
use Orba\Magento2Codegen\Model\InputPropertyInterface;
use Orba\Magento2Codegen\Model\PropertyInterface;
use Orba\Magento2Codegen\Model\StringProperty;
use Orba\Magento2Codegen\Util\PropertyBag;
use Symfony\Component\Console\Exception\InvalidArgumentException as ConsoleInvalidArgumentException;
use Symfony\Component\Console\Question\Question;
use Orba\Magento2Codegen\Service\IO;
use Orba\Magento2Codegen\Service\StringValidator;

class StringCollector extends AbstractInputCollector
{
    /**
     * @var StringValidator
     */
    protected $stringValidator;

    /**
     * @param IO $io
     * @param StringValidator $stringValidator
     */
    public function __construct(
        IO $io,
        StringValidator $stringValidator
    ) {
        parent::__construct($io);
        $this->stringValidator = $stringValidator;
    }

    protected function validateProperty(PropertyInterface $property): void
    {
        if (!$property instanceof StringProperty) {
            throw new InvalidArgumentException('Invalid property type.');
        }
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
                $stringValidator->validate($answer, $propertyValidators);
            }
            return $answer;
        });

        return (string) $this->io->getInstance()->askQuestion($question);
    }
}
