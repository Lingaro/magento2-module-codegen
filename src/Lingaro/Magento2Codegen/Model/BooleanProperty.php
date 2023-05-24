<?php

/**
 * Copyright © 2023 Lingaro sp. z o.o. All rights reserved.
 * See LICENSE for license details.
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
