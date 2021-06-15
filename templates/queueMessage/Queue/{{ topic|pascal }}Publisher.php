<?php

/**
 * @copyright Copyright © {{ commentsYear }} {{ commentsCompanyName|raw }}. All rights reserved.
 * @author    {{ commentsUserEmail }}
 */

declare(strict_types=1);

namespace {{ vendorName|pascal }}\{{ moduleName|pascal }}\Queue;

use Magento\Framework\MessageQueue\Publisher as FrameworkPublisher;
use {{ vendorName|pascal }}\{{ moduleName|pascal }}\Api\Data\{{ topic|pascal }}RequestInterface;

class {{ topic|pascal }}Publisher
{
    public const TOPIC = '{{ vendorName|snake }}.{{ moduleName|snake }}.{{ topic|snake }}';

    private FrameworkPublisher $frameworkPublisher;

    public function __construct(FrameworkPublisher $frameworkPublisher)
    {
        $this->frameworkPublisher = $frameworkPublisher;
    }

    public function publish({{ topic|pascal }}RequestInterface $request): void
    {
        $this->frameworkPublisher->publish(self::TOPIC, $request);
    }
}
