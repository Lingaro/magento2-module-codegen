<?php

namespace Orba\Magento2Codegen\Model;

interface PropertyInterface
{
    /**
     * @param string $value
     * @return PropertyInterface
     */
    public function setName(string $value): PropertyInterface;

    /**
     * @return string|null
     */
    public function getName(): ?string;

    /**
     * @param string $value
     * @return PropertyInterface
     */
    public function setDescription(string $value): PropertyInterface;

    /**
     * @return string|null
     */
    public function getDescription():? string;
}
