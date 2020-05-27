<?php
declare(strict_types=1);

namespace Orba\Magento2Codegen\Service\FileMerger;

use InvalidArgumentException;
use Orba\Magento2Codegen\Service\Magento\ConfigCsvI18nMergerFactory;
use Throwable;

class CsvI18nMerger extends AbstractMerger implements MergerInterface
{
    /**
     * @var ConfigCsvI18nMergerFactory
     */
    private $mergerFactory;

    /**
     * CsvI18nMerger constructor.
     * @param ConfigCsvI18nMergerFactory $mergerFactory
     */
    public function __construct(ConfigCsvI18nMergerFactory $mergerFactory)
    {
        $this->mergerFactory = $mergerFactory;
    }

    /**
     * @param string $oldContent
     * @param string $newContent
     * @return string
     */
    public function merge(string $oldContent, string $newContent): string
    {
        $merger = $this->mergerFactory->create(
            $oldContent
        );

        try {
            $mergedCsv = $merger->merge($newContent);

            return $merger->saveCsv($mergedCsv);
        } catch (Throwable $t) {
            throw new InvalidArgumentException('Invalid CSV File.');
        }
    }

}
