{% if _item.source_model == 'custom' %}
<?php

/**
 * @copyright Copyright © {{ commentsYear }} {{ commentsCompanyName}}. All rights reserved.
 * @author    {{ commentsUserEmail }}
 */

declare(strict_types=1);

namespace {{ vendorName|pascal }}\{{ moduleName|pascal }}\Model\Product\Attribute\Source;

class {{ _item.code|pascal }}Source extends \Magento\Eav\Model\Entity\Attribute\Source\AbstractSource
{
    public function getAllOptions(): array
    {
{% if _item.source_model_custom is not empty %}
        if ($this->_options === null) {
            $this->_options = [
{% for item in _item.source_model_custom %}
                ['value' => '{{ item.value }}', 'label' => __('{{ item.label }}')],
{% endfor %}
            ];
        }
        return $this->_options;
{% endif %}
    }
}
{% endif %}
