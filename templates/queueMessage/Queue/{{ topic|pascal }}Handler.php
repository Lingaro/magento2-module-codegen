<?php

/**
 * @copyright Copyright © {{ commentsYear }} {{ commentsCompanyName }}. All rights reserved.
 * @author    {{ commentsUserEmail }}
 */

declare(strict_types=1);

namespace {{ vendorName|pascal }}\{{ moduleName|pascal }}\Queue;

use {{ vendorName|pascal }}\{{ moduleName|pascal }}\Api\Data\{{ topic|pascal }}RequestInterface;

class {{ topic|pascal }}Handler
{
    public function process({{ topic|pascal }}RequestInterface $message): void
    {
        // Add your code here...
    }
}
