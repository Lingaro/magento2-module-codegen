<?php

/**
 * Copyright © 2023 Lingaro sp. z o.o. All rights reserved.
 * See LICENSE for license details.
 */

declare(strict_types=1);

namespace Orba\Magento2Codegen\Service\PropertyValueCollector;

use InvalidArgumentException;
use Orba\Magento2Codegen\Model\PropertyInterface;

use function array_keys;
use function sprintf;

class CollectorFactory
{
    /**
     * @var <string, <string, CollectorInterface>>
     */
    private array $map;

    public function __construct(array $map = [])
    {
        $this->map = $map;
    }

    public function create(string $type, PropertyInterface $property): CollectorInterface
    {
        if (!isset($this->map[$type][get_class($property)])) {
            throw new InvalidArgumentException(sprintf(
                'There is no collector defined for type "%s" and property "%s".',
                $type,
                get_class($property)
            ));
        }
        return clone $this->map[$type][get_class($property)];
    }

    /**
     * @return string[]
     */
    public function getAllowedTypes(): array
    {
        return array_keys($this->map);
    }
}
