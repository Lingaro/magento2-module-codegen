<?php

namespace Orba\Magento2Codegen\Service;

use Orba\Magento2Codegen\Model\InputPropertyInterface;
use Orba\Magento2Codegen\Util\PropertyBag;
use RuntimeException;

class PropertyDependencyChecker
{
    public function areRootConditionsMet(InputPropertyInterface $property, PropertyBag $propertyBag): bool
    {
        if ($property->getDepend()) {
            foreach ($property->getDepend() as $propertyKey => $propertyValue) {
                if (strpos($propertyKey, '.') === false) {
                    if (!isset($propertyBag[$propertyKey])) {
                        throw new RuntimeException(
                            sprintf('Dependency on non-existent property "%s"', $propertyKey)
                        );
                    }
                    if ($propertyBag[$propertyKey] != $propertyValue) {
                        return false;
                    }
                }
            }
        }
        return true;
    }

    public function areScopeConditionsMet(string $scope, InputPropertyInterface $property, array $values): bool
    {
        if ($property->getDepend()) {
            foreach ($property->getDepend() as $propertyKey => $propertyValue) {
                $propertyKeyArray = explode('.', $propertyKey, 2);
                if (count($propertyKeyArray) === 2 && $propertyKeyArray[0] === $scope) {
                    if (!isset($values[$propertyKeyArray[1]])) {
                        throw new RuntimeException(
                            sprintf('Dependency on non-existent property "%s"', $propertyKey)
                        );
                    }
                    if ($values[$propertyKeyArray[1]] != $propertyValue) {
                        return false;
                    }
                }
            }
        }
        return true;
    }
}
