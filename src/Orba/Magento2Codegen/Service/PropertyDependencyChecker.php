<?php

/**
 * @copyright Copyright © 2021 Orba. All rights reserved.
 * @author    info@orba.co
 */

declare(strict_types=1);

namespace Orba\Magento2Codegen\Service;

use Orba\Magento2Codegen\Model\InputPropertyInterface;
use Orba\Magento2Codegen\Util\PropertyBag;
use RuntimeException;

use function array_key_exists;
use function count;
use function explode;
use function sprintf;
use function strpos;

class PropertyDependencyChecker
{
    public function areRootConditionsMet(InputPropertyInterface $property, PropertyBag $propertyBag): bool
    {
        if ($property->getDepend()) {
            foreach ($property->getDepend() as $propertyKey => $propertyValue) {
                if (strpos($propertyKey, '.') === false) {
                    if (!array_key_exists($propertyKey, $propertyBag->toArray())) {
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
                    if (!array_key_exists($propertyKeyArray[1], $values)) {
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
