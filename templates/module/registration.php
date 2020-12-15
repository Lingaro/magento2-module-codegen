<?php
/*
 * @copyright Copyright © {{ commentsYear }} {{ commentsCompanyName|raw }}. All rights reserved.
 * @author    {{ commentsUserEmail }}
 */

\Magento\Framework\Component\ComponentRegistrar::register(
    \Magento\Framework\Component\ComponentRegistrar::MODULE,
    '{{ vendorName|pascal }}_{{ moduleName|pascal }}',
    __DIR__
);
