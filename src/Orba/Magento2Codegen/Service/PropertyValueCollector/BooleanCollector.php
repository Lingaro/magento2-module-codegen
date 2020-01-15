<?php

namespace Orba\Magento2Codegen\Service\PropertyValueCollector;

use InvalidArgumentException;
use Orba\Magento2Codegen\Model\BooleanProperty;
use Orba\Magento2Codegen\Model\PropertyInterface;
use Orba\Magento2Codegen\Service\IO;


class BooleanCollector extends AbstractInputCollector
{

    public function __construct(IO $io)
    {
        parent::__construct($io);
        $this->setQuestionPrefix('Use boolean value ');
    }

    protected function validateProperty(PropertyInterface $property): void
    {
        if (!$property instanceof BooleanProperty) {
            throw new InvalidArgumentException('Invalid property type.');
        }
    }

    /**
     * @inheritDoc
     */
    protected function collectValueFromInput(PropertyInterface $property)
    {
        /** @var BooleanProperty $property */
        $ret = $this->io->getInstance()
            ->ask($this->questionPrefix . $property->getName(), $property->getDefaultValue());
        return $property::convertToBool($ret);
    }
}