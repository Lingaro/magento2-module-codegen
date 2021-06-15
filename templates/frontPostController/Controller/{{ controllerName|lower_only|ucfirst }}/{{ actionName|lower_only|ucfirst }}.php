<?php

/**
 * @copyright Copyright © {{ commentsYear }} {{ commentsCompanyName|raw }}. All rights reserved.
 * @author    {{ commentsUserEmail }}
 */

declare(strict_types=1);

namespace {{ vendorName|pascal }}\{{ moduleName|pascal }}\Controller\{{ controllerName|lower_only|ucfirst }};

{% set parent_class = 'Action' %}
{% if actionName|lower_only|ucfirst == parent_class %}
{% set parent_class = 'BaseAction' %}
{% endif %}
use Magento\Framework\App\Action\Action{% if parent_class != 'Action' %} as {{ parent_class }}{% endif %};
use Magento\Framework\App\Action\HttpPostActionInterface;

class {{ actionName|lower_only|ucfirst }} extends {{ parent_class }} implements HttpPostActionInterface
{
    public function execute()
    {
        // Put your logic here
        return $this->_redirect('/');
    }
}
