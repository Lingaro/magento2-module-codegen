<?php

namespace Orba\Magento2Codegen\Service\PropertyValueCollector;

use Orba\Magento2Codegen\Model\PropertyInterface;
use Orba\Magento2Codegen\Service\IO;
use Orba\Magento2Codegen\Util\PropertyBag;

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

    protected function _collectValue(PropertyInterface $property, PropertyBag $propertyBag)
    {
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
     * @param PropertyInterface $property
     * @param PropertyBag $propertyBag
     * @return mixed
     */
    protected abstract function collectValueFromInput(PropertyInterface $property, PropertyBag $propertyBag);

}
