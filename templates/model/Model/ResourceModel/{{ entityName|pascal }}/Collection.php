<?php

/**
 * @copyright Copyright © {{ commentsYear }} {{ commentsCompanyName }}. All rights reserved.
 * @author    {{ commentsUserEmail }}
 */

namespace {{ vendorName|pascal }}\{{ moduleName|pascal }}\Model\ResourceModel\{{ entityName|pascal }};

use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;
use {{ vendorName|pascal }}\{{ moduleName|pascal }}\Model\{{ entityName|pascal }};
use {{ vendorName|pascal }}\{{ moduleName|pascal }}\Model\ResourceModel\{{ entityName|pascal }} as {{ entityName|pascal }}ResourceModel;

class Collection extends AbstractCollection
{
    /**
     * @var string
     */
    protected $_idFieldName = 'entity_id';

    /**
     * @return void
     */
    protected function _construct()
    {
        $this->_init({{ entityName|pascal }}::class, {{ entityName|pascal }}ResourceModel::class);
    }
}
