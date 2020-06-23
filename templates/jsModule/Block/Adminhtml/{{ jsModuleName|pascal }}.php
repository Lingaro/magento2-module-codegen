{% if withBlock == true and area == 'adminhtml' %}<?php
/**
 * @copyright Copyright © {{ commentsYear }} {{ commentsCompanyName }}. All rights reserved.
 * @author    {{ commentsUserEmail }}
 */

namespace {{ vendorName|pascal }}\{{ moduleName|pascal }}\Block\Adminhtml;

use Magento\Backend\Block\Template;
use Magento\Framework\Serialize\Serializer\Json;
use Magento\Backend\Block\Template\Context;

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
