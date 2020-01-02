<?php

/**
 * @copyright Copyright © {{ commentsYear }} {{ commentsCompanyName }}. All rights reserved.
 * @author    {{ commentsUserEmail }}
 */
 
namespace {{ vendorName|pascal }}\{{ moduleName|pascal }}\Controller\{{ controllerName|pascal }}};

use Magento\Framework\App\Action\Action;
use Magento\Framework\App\Action\HttpGetActionInterface;
use Magento\Framework\App\Action\HttpPostActionInterface;
use Magento\Framework\View\Result\PageFactory;
use Magento\Framework\App\Action\Context;

class {{ actionName|pascal }} extends Action
{%- if httpActionGet and httpActionPost %}
 implements HttpGetActionInterface, HttpPostActionInterface
{% elseif httpActionGet %}
 implements HttpGetActionInterface
{% elseif httpActionPost %}
 implements HttpPostActionInterface
{% endif %}
{
    /**
     * @var PageFactory
     */
    protected $pageFactory;

    /**
     * @param Context $context
     * @param PageFactory $pageFactory
     */
    public function __construct(Context $context, PageFactory $pageFactory)
    {
        $this->pageFactory = $pageFactory;
        parent::__construct($context);
    }

    /**
     * {Actionname} Action
     * 
     * @return \Magento\Framework\View\Result\Page
     */
    public function execute()
    {
        /** @var \Magento\Framework\View\Result\Page $resultPage */
        $resultPage = $this->pageFactory->create();
        return $resultPage;
    }
}
