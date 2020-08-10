<?php

namespace Orba\Magento2Codegen\Model;

/**
 * Class BooleanProperty
 * @package Orba\Magento2Codegen\Model
 */
class BooleanProperty extends AbstractInputProperty
{
    public function getDefaultValue()
    {
        return (bool) parent::getDefaultValue();
    }
}
