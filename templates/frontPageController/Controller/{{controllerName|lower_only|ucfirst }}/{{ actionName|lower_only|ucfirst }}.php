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
use Magento\Framework\App\Action\Context;
use Magento\Framework\App\Action\HttpGetActionInterface;
use Magento\Framework\View\Result\Page;
use Magento\Framework\View\Result\PageFactory;

class {{ actionName|lower_only|ucfirst }} extends {{ parent_class }} implements HttpGetActionInterface
{
    private PageFactory $pageFactory;

    public function __construct(Context $context, PageFactory $pageFactory)
    {
        $this->pageFactory = $pageFactory;
        parent::__construct($context);
    }

    public function execute(): Page
    {
        /** @var Page $resultPage */
        $resultPage = $this->pageFactory->create();
        return $resultPage;
    }
}
