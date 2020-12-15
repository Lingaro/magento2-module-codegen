<?php

/**
 * @copyright Copyright © {{ commentsYear }} {{ commentsCompanyName|raw }}. All rights reserved.
 * @author    {{ commentsUserEmail }}
 */

namespace {{ vendorName|pascal }}\{{ moduleName|pascal }}\Controller\Adminhtml\{{ entityName|lower_only|ucfirst }};

use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use {{ vendorName|pascal }}\{{ moduleName|pascal }}\Model\{{ entityName|pascal }}Factory;

class Save extends Action
{
    const ADMIN_RESOURCE = '{{ vendorName|pascal }}_{{ moduleName|pascal }}::{{ entityName|snake }}';

    /**
     * @var {{ entityName|camel}}Factory $objectFactory
     */
    protected $objectFactory;

    /**
     * @param Context $context
     * @param {{ entityName|camel}}Factory $objectFactory
     */
    public function __construct(
        Context $context,
        {{ entityName|camel}}Factory $objectFactory
    ) {
        $this->objectFactory = $objectFactory;
        parent::__construct($context);
    }

    /**
     * Save action
     *
     * @return \Magento\Framework\Controller\ResultInterface
     */
    public function execute()
    {
        $data = $this->getRequest()->getParams();
        /** @var \Magento\Backend\Model\View\Result\Redirect $resultRedirect */
        $resultRedirect = $this->resultRedirectFactory->create();
        if ($data) {
            $params = [];
            $objectInstance = $this->objectFactory->create();
            $idField = $objectInstance->getIdFieldName();
            if (empty($data[$idField])) {
                $data[$idField] = null;
            } else {
                $objectInstance->load($data[$idField]);
                $params[$idField] = $data[$idField];
            }
            $objectInstance->addData($data);

            $this->_eventManager->dispatch(
                '{{ vendorName|snake }}_{{ moduleName|snake }}_{{ entityName|snake }}_prepare_save',
                ['object' => $this->objectFactory, 'request' => $this->getRequest()]
            );

            try {
                $objectInstance->save();
                $this->messageManager->addSuccessMessage(__('You saved this record.'));
                $this->_getSession()->setFormData(false);
                if ($this->getRequest()->getParam('back')) {
                    $params = [$idField => $objectInstance->getId(), '_current' => true];
                    return $resultRedirect->setPath('*/*/edit', $params);
                }
                return $resultRedirect->setPath('*/*/');
            } catch (\Exception $e) {
                $this->messageManager->addErrorMessage($e->getMessage());
                $this->messageManager->addExceptionMessage($e, __('Something went wrong while saving the record.'));
            }

            $this->_getSession()->setFormData($this->getRequest()->getPostValue());
            return $resultRedirect->setPath('*/*/edit', $params);
        }
        return $resultRedirect->setPath('*/*/');
    }
}
