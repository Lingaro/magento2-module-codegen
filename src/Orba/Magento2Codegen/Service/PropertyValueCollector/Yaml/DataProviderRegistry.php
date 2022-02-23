<?php

/**
 * @copyright Copyright © 2022 Orba. All rights reserved.
 * @author    info@orba.co
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
