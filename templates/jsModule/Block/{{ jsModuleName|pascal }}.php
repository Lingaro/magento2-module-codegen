{% if withBlock == true and area == 'frontend' %}<?php
/**
 * @copyright Copyright © {{ commentsYear }} {{ commentsCompanyName }}. All rights reserved.
 * @author    {{ commentsUserEmail }}
 */

namespace {{ vendorName|pascal }}\{{ moduleName|pascal }}\Block;

use Magento\Framework\View\Element\Template;
use Magento\Framework\Serialize\Serializer\Json;
use Magento\Framework\View\Element\Template\Context;

class {{ jsModuleName|pascal }} extends Template
{
    /**
     * @var Json
     */
    private $serializer;

    public function __construct(
        Context $context,
        Json $serializer
    ) {
        parent::__contruct($context);
        $this->serialzier = $serializer;
    }

    public function get{{ jsModuleName|pascal }}ConfigJson() {
        return $this->serializer->serialize([
            '{{ jsModuleName|snake }}' => []
        ]);
    }
}
{% endif %}
