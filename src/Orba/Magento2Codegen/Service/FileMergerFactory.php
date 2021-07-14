<?php

/**
 * @copyright Copyright © 2021 Orba. All rights reserved.
 * @author    info@orba.co
 */

declare(strict_types=1);

namespace Orba\Magento2Codegen\Service;

use Orba\Magento2Codegen\Service\FileMerger\MergerInterface;

use function preg_match;

class FileMergerFactory
{
    /**
     * @var MergerInterface[]
     */
    private array $mergers = [];

    public function addMerger(string $fileNameRegex, MergerInterface $merger, array $params = []): void
    {
        $merger->setParams($params);
        $this->mergers[$fileNameRegex] = $merger;
    }

    public function create(string $fileName): ?MergerInterface
    {
        foreach ($this->mergers as $fileNameRegex => $merger) {
            if (preg_match($fileNameRegex, $fileName)) {
                return $merger;
            }
        }
        return null;
    }
}
