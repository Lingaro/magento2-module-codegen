<?php

/**
 * @copyright Copyright © {{ commentsYear }} {{ commentsCompanyName|raw }}. All rights reserved.
 * @author    {{ commentsUserEmail }}
 */

namespace {{ vendorName|pascal }}\{{ moduleName|pascal }}\Model;

use Magento\Framework\Model\AbstractModel;
use {{ vendorName|pascal }}\{{ moduleName|pascal }}\Api\Data\{{ entityName|pascal }}Interface;
use {{ vendorName|pascal }}\{{ moduleName|pascal }}\Model\ResourceModel\{{ entityName|pascal }} as {{ entityName|pascal }}ResourceModel;

class {{ entity2Name|pascal }} extends AbstractModel implements {{ entity2Name|pascal }}Interface
{

    /**
     * @return int|null
     */
    public function get{{ entityName|pascal }}Id()
    {
        return $this->getData('{{ entityName|snake }}_id');
    }

    /**
     * @param int $value
     * @return void
     */
    public function set{{ entityName|pascal }}Id($value)
    {
        $this->setData('{{ entityName|snake }}_id', $value);
    }

}
