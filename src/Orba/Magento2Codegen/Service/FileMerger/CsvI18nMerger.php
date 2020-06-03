<?php
declare(strict_types=1);

namespace Orba\Magento2Codegen\Service\FileMerger;

use Exception;
use InvalidArgumentException;
use Orba\Magento2Codegen\Util\Magento\Config\CsvI18n;

class CsvI18nMerger extends AbstractMerger implements MergerInterface
{
    /**
     * @var CsvI18n
     */
    private $merger;

    /**
     * CsvI18nMerger constructor.
     * @param CsvI18n $merger
     */
    public function __construct(CsvI18n $merger)
    {
        $this->merger = $merger;
    }

    /**
     * @param string $oldContent
     * @param string $newContent
     * @return string
     * @throws Exception
     */
    public function merge(string $oldContent, string $newContent): string
    {
        $mergedCsv = $this->merger->merge($oldContent, $newContent);

        return $this->merger->generateContent($mergedCsv);

    }

}
