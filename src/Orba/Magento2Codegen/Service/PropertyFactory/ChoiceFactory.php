<?php

/**
 * @copyright Copyright © 2021 Orba. All rights reserved.
 * @author    info@orba.co
 */

declare(strict_types=1);

namespace Orba\Magento2Codegen\Service\PropertyFactory;

use Orba\Magento2Codegen\Model\ChoiceProperty;
use Orba\Magento2Codegen\Model\PropertyInterface;

class ChoiceFactory extends AbstractFactory implements FactoryInterface
{
    public function create(string $name, array $config): PropertyInterface
    {
        $property = new ChoiceProperty();
        $this->propertyBuilder
            ->addName($property, $name)
            ->addDescription($property, $config)
            ->addDepend($property, $config)
            ->addOptions($property, $config)
            ->addDefaultValue($property, $config);
        return $property;
    }
}
