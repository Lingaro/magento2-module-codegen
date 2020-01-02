<?php

/**
 * @copyright Copyright © {{ commentsYear }} {{ commentsCompanyName }}. All rights reserved.
 * @author    {{ commentsUserEmail }}
 */

namespace {{ vendorName|pascal }}\{{ moduleName|pascal }}\Model;

use Magento\Framework\Model\AbstractModel;
use {{ vendorName|pascal }}\{{ moduleName|pascal }}\Api\Data\{{ entityName|pascal }}Interface;
use {{ vendorName|pascal }}\{{ moduleName|pascal }}\Model\ResourceModel\{{ entityName|pascal }} as {{ entityName|pascal }}ResourceModel;

class {{ entityName|pascal }} extends AbstractModel implements {{ entityName|pascal }}Interface
{
    /**
     * @return void
     */
    protected function _construct()
    {
        $this->_init({{ entityName|pascal }}ResourceModel::class);
    }
{% for item in fields %}

    /**
     * @inheritDoc
     */
    public function get{{ item.name|pascal }}(): ?{{ item.php_type }}
    {
        return $this->getData('{{ item.name|snake }}');
    }

    /**
    * @inheritDoc
    */
    public function set{{ item.name|pascal }}({{ item.php_type }} $value): void
    {
        $this->setData('{{ item.name|snake }}', $value);
    }
{% endfor %}
}
