<?php

namespace Orba\Magento2Codegen\Service;

use Orba\Magento2Codegen\Service\FileMerger\MergerInterface;

class FileMergerFactory
{
    /**
     * @var MergerInterface[]
     */
    private $mergers = [];

    public function addMerger(string $fileNameRegex, MergerInterface $merger, array $params = []): void
    {
        $merger->setParams($params);
        $this->mergers[$fileNameRegex] = $merger;
    }

    public function create(string $fileName):? MergerInterface
    {
        foreach ($this->mergers as $fileNameRegex => $merger) {
            echo $fileName . PHP_EOL;
            echo $fileNameRegex . PHP_EOL;
            if (preg_match($fileNameRegex, $fileName)) {
                return $merger;
            }
        }
        return null;
    }
}