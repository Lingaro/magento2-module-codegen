<?php
declare(strict_types=1);

namespace Orba\Magento2Codegen\Service\FileMerger;

use Exception;
use Orba\Magento2Codegen\Service\FileMerger\CsvI18nMerger\Processor;

class CsvI18nMerger extends AbstractMerger implements MergerInterface
{
    /**
     * @var Processor
     */
    private $processor;

    public function __construct(Processor $processor)
    {
        $this->processor = $processor;
    }

    /**
     * @param string $oldContent
     * @param string $newContent
     * @return string
     * @throws Exception
     */
    public function merge(string $oldContent, string $newContent): string
    {
        $mergedCsv = $this->processor->merge($oldContent, $newContent);
        return $this->processor->generateContent($mergedCsv);
    }
}
