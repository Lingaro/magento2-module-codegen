<?php

/**
 * @copyright Copyright © {{ commentsYear }} {{ commentsCompanyName }}. All rights reserved.
 * @author    {{ commentsUserEmail }}
 */

namespace {{ vendorName|pascal }}\{{ moduleName|pascal }}\Api\Data;

use Magento\Framework\Api\ExtensibleDataInterface;

interface {{ entityName|pascal }}Interface extends ExtensibleDataInterface
{
    {% for item in fields %}
    /**
     * @return {{ item.type }}|null
     */
    public function get{{ item.name|pascal }}(): {{ item.type }};

    /**
     * @param {{ item.type }} $value
     * @return $this
     */
    public function set{{ item.name|pascal }}({{ item.type }} $value): $this;

    {% endfor %}

    /**
     * @return \{{ vendorName|pascal }}\{{ moduleName|pascal }}\Api\Data\{{ entityName|pascal }}ExtensionInterface|null
     */
    public function getExtensionAttributes();

    /**
     * @param \{{ vendorName|pascal }}\{{ moduleName|pascal }}\Api\Data\{{ entityName|pascal }}ExtensionInterface $extensionAttributes
     * @return $this
     */
    public function setExtensionAttributes(\{{ vendorName|pascal }}\{{ moduleName|pascal }}\Api\Data\{{ entityName|pascal }}ExtensionInterface $extensionAttributes);
}
