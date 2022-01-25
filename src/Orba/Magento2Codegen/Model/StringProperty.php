<?php

/**
 * @copyright Copyright © 2021 Orba. All rights reserved.
 * @author    info@orba.co
 */

declare(strict_types=1);

namespace Orba\Magento2Codegen\Model;

class StringProperty extends AbstractInputProperty
{
    /**
     * @var array
     */
    protected $validators = [];

    /**
     * @return array
     */
    public function getValidators(): array
    {
        return $this->validators;
    }

    /**
     * @param array $value
     * @return InputPropertyInterface
     */
    public function setValidators(array $value): InputPropertyInterface
    {
        $this->validators = $value;
        return $this;
    }
}
