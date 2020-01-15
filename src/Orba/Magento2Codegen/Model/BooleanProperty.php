<?php

namespace Orba\Magento2Codegen\Model;

class BooleanProperty extends AbstractProperty
{

    private $defaultValue;

    public function __construct(string $name)
    {
        $this->setName($name);
    }

    /**
     * @param mixed $value
     * @return $this
     */
    public function setDefaultValue($value): PropertyInterface
    {
        $this->defaultValue = self::convertToBool($value);
        return $this;
    }

    public function getDefaultValue(): ?string
    {
        if (isset($this->defaultValue)) {
            return $this->defaultValue ? 'yes' : 'no';
        } else {
            return null;
        }
    }

    /**
     * @param mixed $value
     * @return bool
     */
    public static function convertToBool($value): bool
    {
        if (is_string($value)) {
            $value = strtolower($value);
            switch ($value) {
                case 'no':
                case 'false':
                    return false;
            }
        }
        return boolval($value);
    }
}
