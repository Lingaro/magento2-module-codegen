<?php

/**
 * @copyright Copyright © 2021 Orba. All rights reserved.
 * @author    info@orba.co
 */

declare(strict_types=1);

namespace Orba\Magento2Codegen\Service\PropertyValueCollector;

use InvalidArgumentException;
use Orba\Magento2Codegen\Model\PropertyInterface;

class CollectorFactory
{
    /**
     * @var CollectorInterface[]
     */
    private array $map;

    public function __construct(array $map = [])
    {
        $this->map = $map;
    }

    public function create(PropertyInterface $property): CollectorInterface
    {
        if (!isset($this->map[get_class($property)])) {
            throw new InvalidArgumentException('There is no collector defined for this property.');
        }
        return $this->map[get_class($property)];
    }
}
