<?php

namespace Orba\Magento2Codegen\Model;

abstract class AbstractProperty implements PropertyInterface
{
    /**
     * @var string|null
     */
    protected $name;

    /**
     * @var string|null
     */
    protected $description;

    /**
     * @var bool
     */
    protected $required = false;

    /**
     * @param string $value
     * @return $this
     */
    public function setName(string $value): PropertyInterface
    {
        $this->name = $value;
        return $this;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    /**
     * @param string $value
     * @return $this
     */
    public function setDescription(string $value): PropertyInterface
    {
        $this->description = $value;
        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    /**
     * @param string $value
     * @return $this|PropertyInterface
     */
    public function setRequired(string $value): PropertyInterface
    {
        $this->required = (bool)$value;
        return $this;
    }

    /**
     * @return bool
     */
    public function getRequired(): bool
    {
        return $this->required;
    }
}
