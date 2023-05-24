<?php

/**
 * Copyright © 2023 Lingaro sp. z o.o. All rights reserved.
 * See LICENSE for license details.
 */

declare(strict_types=1);

namespace Orba\Magento2Codegen\Service\CommandUtil;

use Orba\Magento2Codegen\Service\Magento\Detector;

class Root
{
    private Template $templateCommandUtil;
    private Detector $detector;

    public function __construct(Template $templateCommandUtil, Detector $detector)
    {
        $this->templateCommandUtil = $templateCommandUtil;
        $this->detector = $detector;
    }

    public function isCurrentDirMagentoRoot(): bool
    {
        return $this->detector->rootEtcFileExistsInDir($this->templateCommandUtil->getRootDir());
    }
}
