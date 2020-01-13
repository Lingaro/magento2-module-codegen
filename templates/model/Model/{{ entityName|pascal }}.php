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

    /**
     * @return int|null
     */
    public function getId()
    {
        return $this->_getData('entity_id');
    }

    /**
     * @param int $value
     * @return void
     */
    public function setId($value)
    {
        $this->setData('entity_id', $value);
    }
{% for item in fields %}

    /**
     * @return {{ databaseTypeToPHP(item.databaseType) }}|null
     */
    public function get{{ item.name|pascal }}()
    {
        return $this->getData('{{ item.name|snake }}');
    }

    /**
     * @param {{ databaseTypeToPHP(item.databaseType) }} $value
     * @return void
     */
    public function set{{ item.name|pascal }}({{ databaseTypeToPHP(item.databaseType) }} $value)
    {
        $this->setData('{{ item.name|snake }}', $value);
    }
{% endfor %}
}
