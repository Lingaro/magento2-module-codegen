<?php

/**
 * @copyright Copyright © 2021 Orba. All rights reserved.
 * @author    info@orba.co
 */

declare(strict_types=1);

namespace Orba\Magento2Codegen\Service\FileMerger\Formatter;

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
