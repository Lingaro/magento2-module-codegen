<?php

/**
 * @copyright Copyright © 2022 Orba. All rights reserved.
 * @author    info@orba.co
 */

declare(strict_types=1);

namespace Orba\Magento2Codegen\Service\PropertyValueCollector\Yaml;

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
