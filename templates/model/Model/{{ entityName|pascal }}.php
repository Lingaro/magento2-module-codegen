<?php

/**
 * @copyright Copyright © {{ commentsYear }} {{ commentsCompanyName }}. All rights reserved.
 * @author    {{ commentsUserEmail }}
 */

namespace {{ vendorName|pascal }}\{{ moduleName|pascal }}\Model;

use Magento\Framework\Model\AbstractExtensibleModel;
use {{ vendorName|pascal }}\{{ moduleName|pascal }}\Api\Data\{{ entityName|pascal }}ExtensionInterface;
use {{ vendorName|pascal }}\{{ moduleName|pascal }}\Api\Data\{{ entityName|pascal }}Interface;

class {{ entityName|pascal }} extends AbstractExtensibleModel implements {{ entityName|pascal }}Interface
{
    /**
     * Initialize resource model
     * @return void
     */
    protected function _construct()
    {
        $this->_init('{{ vendorName|pascal }}\{{ moduleName|pascal }}\Model\ResourceModel\{{ entityName|pascal }}');
    }

    {% for item in fields %}
    /**
     * @inheritDoc
     */
    public function get{{ item.name|pascal }}(): {{ item.type }}
    {
        return $this->getData('{{ item.name }}');
    }

    /**
    * @inheritDoc
    */
    public function set{{ item.name|pascal }}({{ item.type }} $value): $this
    {
        $this->setData('{{ item.name }}', $value);
    }

    {% endfor %}

    /**
     * @inheritDoc
     */
    public function getExtensionAttributes()
    {
        return $this->_getExtensionAttributes();
    }

    /**
     * @inheritDoc
     */
    public function setExtensionAttributes({{ entityName|pascal }}ExtensionInterface $extensionAttributes)
    {
        $this->_setExtensionAttributes($extensionAttributes);
        return $this;
    }
}
