<?php

namespace Orba\Magento2Codegen\Service\PropertyValueCollector;

use Orba\Magento2Codegen\Model\PropertyInterface;
use Orba\Magento2Codegen\Service\IO;

abstract class AbstractInputCollector extends AbstractCollector
{
    /**
     * @var IO
     */
    protected $io;

    /**
     * @var string
     */
    protected $questionPrefix = '';

    public function __construct(IO $io)
    {
        $this->io = $io;
    }

    public function setQuestionPrefix(string $prefix): void
    {
        $this->questionPrefix = $prefix;
    }

    protected function _collectValue(PropertyInterface $property)
    {
        $description = $property->getDescription();
        if ($description) {
            $this->io->getInstance()->block($description, $property->getName());
        }
        return $this->collectValueFromInput($property);
    }

    /**
     * @param PropertyInterface $property
     * @return mixed
     */
    protected abstract function collectValueFromInput(PropertyInterface $property);

}