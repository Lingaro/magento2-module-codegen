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
     * @var array|null
     */
    protected $depend;

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
     * @param array|null $value
     * @return $this
     */
    public function setDepend(?array $value): PropertyInterface
    {
        $this->depend = $value;
        return $this;
    }

    public function getDepend(): ?array
    {
        return $this->depend;
    }
}
