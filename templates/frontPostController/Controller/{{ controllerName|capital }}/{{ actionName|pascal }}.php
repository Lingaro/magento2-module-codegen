<?php

/**
 * @copyright Copyright © {{ commentsYear }} {{ commentsCompanyName }}. All rights reserved.
 * @author    {{ commentsUserEmail }}
 */

namespace {{ vendorName|pascal }}\{{ moduleName|pascal }}\Controller\{{ controllerName|capital }};

use Magento\Framework\App\Action\Action;
use Magento\Framework\App\Action\HttpPostActionInterface;

class {{ actionName|pascal }} extends Action implements HttpPostActionInterface
{
    public function execute()
    {
        //put your work here
        return $this->_redirect('/');
    }
}
