<?php

/**
 * Copyright © 2023 Lingaro sp. z o.o. All rights reserved.
 * See LICENSE for license details.
 */

declare(strict_types=1);

namespace Lingaro\Magento2Codegen\Service\FileMerger\Formatter;

/**
 * Copy of original magento interface \Magento\Framework\App\DeploymentConfig\Writer\FormatterInterface
 * used for deployment configurations formatting
 */
interface FormatterInterface
{
    /**
     * Format deployment configuration
     */
    public function format(array $data, array $comments = []): string;
}
