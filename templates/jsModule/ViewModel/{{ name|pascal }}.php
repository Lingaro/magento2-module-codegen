{% if withBlock %}<?php
/**
 * @copyright Copyright © {{ commentsYear }} {{ commentsCompanyName }}. All rights reserved.
 * @author    {{ commentsUserEmail }}
 */

namespace {{ vendorName|pascal }}\{{ moduleName|pascal }}\ViewModel;

use Magento\Framework\View\Element\Block\ArgumentInterface;
use Magento\Framework\Serialize\Serializer\Json;

class {{ name|pascal }} implements ArgumentInterface
{
    /**
     * @var Json
     */
    private $serializer;

    public function __construct(
        Json $serializer
    ) {
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
