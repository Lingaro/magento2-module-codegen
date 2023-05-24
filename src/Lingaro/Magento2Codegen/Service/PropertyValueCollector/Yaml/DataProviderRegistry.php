<?php

/**
 * Copyright © 2023 Lingaro sp. z o.o. All rights reserved.
 * See LICENSE for license details.
 */

declare(strict_types=1);

namespace Orba\Magento2Codegen\Service\PropertyValueCollector\Yaml;

class DataProviderRegistry
{
    private DataProvider $dataProvider;

    public function set(DataProvider $dataProvider): void
    {
        $this->dataProvider = $dataProvider;
    }

    public function get(): DataProvider
    {
        return $this->dataProvider;
    }
}
