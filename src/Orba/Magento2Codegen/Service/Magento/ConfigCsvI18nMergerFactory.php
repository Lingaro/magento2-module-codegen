<?php
declare(strict_types=1);

namespace Orba\Magento2Codegen\Service\Magento;

use Orba\Magento2Codegen\Util\Magento\Config\CsvI18n;

class ConfigCsvI18nMergerFactory
{

    public function create(string $initialContent): CsvI18n
    {
        return new CsvI18n($initialContent);
    }
}
