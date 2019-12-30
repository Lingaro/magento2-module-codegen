<?php

/**
 * @copyright Copyright © {{ commentsYear }} {{ commentsCompanyName }}. All rights reserved.
 * @author    {{ commentsUserEmail }}
 */
namespace {{ vendorName|pascal }}\{{ moduleName|pascal }}\Model\ResourceModel;

use Magento\Framework\Model\ResourceModel\Db\AbstractDb;

class {{ entityName|pascal }} extends AbstractDb
{
    /**
     * Initialize resource model
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init('{{ vendorName|snake }}_{{ moduleName|snake }}_{{ entityName|snake }}', 'entity_id');
    }
}
