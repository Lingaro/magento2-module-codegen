<?php

/**
 * @copyright Copyright © {{ commentsYear }} {{ commentsCompanyName|raw }}. All rights reserved.
 * @author    {{ commentsUserEmail }}
 */

declare(strict_types=1);

namespace {{ vendorName|pascal }}\{{ moduleName|pascal }}\Model\ResourceModel;

use Magento\Framework\Model\ResourceModel\Db\AbstractDb;

class {{ entityName|pascal }} extends AbstractDb
{
    protected function _construct(): void
    {
        $this->_init('{{ vendorName|snake }}_{{ moduleName|snake }}_{{ entityName|snake }}', 'entity_id');
    }
}
