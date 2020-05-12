<?php

namespace Orba\Magento2Codegen\Service\PropertyValueCollector;

use InvalidArgumentException;
use Orba\Magento2Codegen\Model\PropertyInterface;

class CollectorFactory
{
    /**
     * @var CollectorInterface[]
     */
    private $map;

    public function __construct(array $map = [])
    {
        $this->map = $map;
    }

    public function create(PropertyInterface $property): CollectorInterface
    {
        if (!isset($this->map[get_class($property)])) {
            throw new InvalidArgumentException('There is no collector defined for this property.');
        }
        /** @var CollectorInterface $collector */
        $collector = $this->map[get_class($property)];
        return $collector;
    }
}