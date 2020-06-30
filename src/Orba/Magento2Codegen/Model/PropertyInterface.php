<?php

namespace Orba\Magento2Codegen\Model;

interface PropertyInterface
{
    public function setName(string $value): PropertyInterface;

    public function getName(): ?string;

    public function setDescription(string $value): PropertyInterface;

    public function getDescription():? string;
}
