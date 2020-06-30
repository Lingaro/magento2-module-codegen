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
     * @param string $value
     * @return $this
     */
    public function setName(string $value): PropertyInterface
    {
        $this->name = $value;
        return $this;
    }

    /**
     * @return string|null
     */
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

    /**
     * @return string|null
     */
    public function getDescription(): ?string
    {
        return $this->description;
    }
}
