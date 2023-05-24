<?php

/**
 * Copyright © 2023 Lingaro sp. z o.o. All rights reserved.
 * See LICENSE for license details.
 */

declare(strict_types=1);

namespace Orba\Magento2Codegen\Model;

interface PropertyInterface
{
    public function setName(string $value): self;

    public function getName(): ?string;

    public function setDescription(string $value): self;

    public function getDescription(): ?string;
}
