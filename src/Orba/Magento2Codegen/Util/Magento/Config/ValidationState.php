<?php

namespace Orba\Magento2Codegen\Util\Magento\Config;

use Magento\Framework\Config\ValidationStateInterface;

class ValidationState implements ValidationStateInterface
{
    /**
     * @inheritDoc
     */
    public function isValidationRequired()
    {
        return false;
    }
}