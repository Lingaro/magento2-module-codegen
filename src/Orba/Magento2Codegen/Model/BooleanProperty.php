<?php

/**
 * @copyright Copyright © 2021 Orba. All rights reserved.
 * @author    info@orba.co
 */

declare(strict_types=1);

namespace Orba\Magento2Codegen\Model;

class BooleanProperty extends AbstractInputProperty
{
    public function getDefaultValue(): bool
    {
        return (bool) parent::getDefaultValue();
    }
}
