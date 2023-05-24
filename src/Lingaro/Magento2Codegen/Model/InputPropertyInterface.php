<?php

/**
 * Copyright © 2023 Lingaro sp. z o.o. All rights reserved.
 * See LICENSE for license details.
 */

declare(strict_types=1);

namespace Orba\Magento2Codegen\Model;

interface InputPropertyInterface extends PropertyInterface
{
    /**
     * @return mixed
     */
    public function getDefaultValue();

    /**
     * @param mixed $value
     */
    public function setDefaultValue($value): self;

    public function getRequired(): bool;

    public function setRequired(bool $value): self;

    public function getDepend(): array;

    public function setDepend(array $value): self;
}
