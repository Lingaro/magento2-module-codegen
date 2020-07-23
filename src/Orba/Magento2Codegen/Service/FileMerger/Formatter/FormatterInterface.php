<?php

namespace Orba\Magento2Codegen\Service\FileMerger\Formatter;

/**
 * Copy of original magento interface \Magento\Framework\App\DeploymentConfig\Writer\FormatterInterface
 * used for deployment configurations formatting
 *
 * Interface FormatterInterface
 * @package Orba\Magento2Codegen\Service\FileMerger\Formatter
 */
interface FormatterInterface
{
    /**
     * Format deployment configuration
     *
     * @param array $data
     * @param array $comments
     * @return string
     */
    public function format($data, array $comments = []);
}
