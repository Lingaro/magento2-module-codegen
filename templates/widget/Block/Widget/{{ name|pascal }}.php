<?php

/**
 * @copyright Copyright © {{ commentsYear }} {{ commentsCompanyName }}. All rights reserved.
 * @author    {{ commentsUserEmail }}
 */

declare(strict_types=1);

namespace {{ vendorName|pascal }}\{{ moduleName|pascal }}\Block\Widget;

use Magento\Framework\View\Element\Template;
use Magento\Widget\Block\BlockInterface;

class {{ name|pascal }} extends Template implements BlockInterface
{
{% if withTemplate == true %}
    protected $_template = "{{ vendorName|pascal }}_{{ moduleName|pascal }}::widget/{{ name|snake }}.phtml";

{% endif %}
    // Write your methods here...
}
