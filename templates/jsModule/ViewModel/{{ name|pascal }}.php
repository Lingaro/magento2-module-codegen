{% if withBlock %}<?php

/**
 * @copyright Copyright © {{ commentsYear }} {{ commentsCompanyName|raw }}. All rights reserved.
 * @author    {{ commentsUserEmail }}
 */

declare(strict_types=1);

namespace {{ vendorName|pascal }}\{{ moduleName|pascal }}\ViewModel;

use Magento\Framework\View\Element\Block\ArgumentInterface;
use Magento\Framework\Serialize\Serializer\Json;

class {{ name|pascal }} implements ArgumentInterface
{
    private Json $serializer;

    public function __construct(Json $serializer)
    {
        $this->serializer = $serializer;
    }

    public function getJsonConfig(): string
    {
        return $this->serializer->serialize([
            '{{ name|camel }}' => []
        ]);
    }
}
{% endif %}
