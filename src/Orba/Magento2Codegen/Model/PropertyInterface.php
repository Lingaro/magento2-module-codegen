<?php

/**
 * @copyright Copyright © 2021 Orba. All rights reserved.
 * @author    info@orba.co
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
