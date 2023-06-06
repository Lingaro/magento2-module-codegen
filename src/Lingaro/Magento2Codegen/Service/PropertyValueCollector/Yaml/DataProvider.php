<?php

/**
 * Copyright © 2023 Lingaro sp. z o.o. All rights reserved.
 * See LICENSE for license details.
 */

declare(strict_types=1);

namespace Lingaro\Magento2Codegen\Service\PropertyValueCollector\Yaml;

use Adbar\Dot;

class DataProvider
{
    private Dot $dot;

    public function __construct(array $data)
    {
        $this->dot = new Dot($data);
    }

    /**
     * @param mixed|null $default
     * @return mixed|null
     */
    public function get(string $key, $default = null)
    {
        return $this->dot->get($key) ?? $default;
    }
}
