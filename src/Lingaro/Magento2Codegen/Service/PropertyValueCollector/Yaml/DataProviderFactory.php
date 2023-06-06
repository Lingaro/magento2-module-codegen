<?php

/**
 * Copyright © 2023 Lingaro sp. z o.o. All rights reserved.
 * See LICENSE for license details.
 */

declare(strict_types=1);

namespace Lingaro\Magento2Codegen\Service\PropertyValueCollector\Yaml;

use InvalidArgumentException;
use Symfony\Component\Yaml\Exception\ParseException;
use Symfony\Component\Yaml\Yaml;

class DataProviderFactory
{
    /**
     * @SuppressWarnings(PHPMD.StaticAccess)
     */
    public function create(string $filePath): DataProvider
    {
        try {
            $data = Yaml::parseFile($filePath);
        } catch (ParseException $e) {
            throw new InvalidArgumentException('Unable to parse the following file: ' . $filePath);
        }
        return new DataProvider($data);
    }
}
