<?php

/**
 * @copyright Copyright © 2021 Orba. All rights reserved.
 * @author    info@orba.co
 */

declare(strict_types=1);

namespace Orba\Magento2Codegen\Service\FileMerger;

abstract class AbstractMerger implements MergerInterface
{
    protected array $params = [];
    protected bool $experimental = false;

    public function setParams(array $params): void
    {
        $this->params = $params;
    }

    public function isExperimental(): bool
    {
        return $this->experimental;
    }
}
