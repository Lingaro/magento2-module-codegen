<?php

namespace Orba\Magento2Codegen\Model;

interface InputPropertyInterface extends PropertyInterface
{
    /**
     * @return mixed
     */
    public function getDefaultValue();

    /**
     * @param mixed $value
     * @return $this
     */
    public function setDefaultValue($value): self;

    /**
     * @return bool
     */
    public function getRequired(): bool;

    /**
     * @param bool $value
     * @return $this
     */
    public function setRequired(bool $value): self;

    /**
     * @return array
     */
    public function getDepend(): array;

    /**
     * @param array $value
     * @return $this
     */
    public function setDepend(array $value): self;
}
