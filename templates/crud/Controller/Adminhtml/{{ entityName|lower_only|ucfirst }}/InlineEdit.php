<?php

/**
 * @copyright Copyright © {{ commentsYear }} {{ commentsCompanyName }}. All rights reserved.
 * @author    {{ commentsUserEmail }}
 */

namespace {{ vendorName|pascal }}\{{ moduleName|pascal }}\Controller\Adminhtml\{{ entityName|lower_only|ucfirst }};

use Exception;
use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Framework\Controller\Result\Json;
use Magento\Framework\Controller\Result\JsonFactory;
use Magento\Framework\Controller\ResultInterface;
use {{ vendorName|pascal }}\{{ moduleName|pascal }}\Api\{{ entityName|pascal }}RepositoryInterface;
use {{ vendorName|pascal }}\{{ moduleName|pascal }}\Model\{{ entityName|pascal }};

/**
 * Grid inline edit controller
 *
 * @SuppressWarnings(PHPMD.CouplingBetweenObjects)
 */
class InlineEdit extends Action
{
    const ADMIN_RESOURCE = '{{ vendorName|pascal }}_{{ moduleName|pascal }}::{{ entityName|snake }}';

    /**
     * @var JsonFactory
     */
    private $jsonFactory;

    /**
     * @var {{ entityName|pascal }}RepositoryInterface
     */
    private $repository;

    /**
     * @param Context $context
     * @param JsonFactory $jsonFactory
     * @param {{ entityName|pascal }}RepositoryInterface $repository
     */
    public function __construct(
        Context $context,
        JsonFactory $jsonFactory,
        {{ entityName|pascal }}RepositoryInterface $repository
    ) {
        parent::__construct($context);
        $this->jsonFactory = $jsonFactory;
        $this->repository = $repository;
    }

    /**
     * @return ResultInterface
     */
    public function execute()
    {
        /** @var Json $resultJson */
        $resultJson = $this->jsonFactory->create();
        $error = false;
        $messages = [];

        $postItems = $this->getRequest()->getParam('items', []);
        if (!($this->getRequest()->getParam('isAjax') && count($postItems))) {
            return $resultJson->setData([
                'messages' => [__('Please correct the data sent.')],
                'error' => true,
            ]);
        }

        try {
            foreach (array_keys($postItems) as ${{ entityName|camel }}Id) {
                /** @var  {{ entityName|pascal }} ${{ entityName|camel }} */
                ${{ entityName|camel }} = $this->repository->getById(${{ entityName|camel }}Id);
                ${{ entityName|camel }}->setData(array_merge(${{ entityName|camel }}->getData(), $postItems[${{ entityName|camel }}Id]));
                $this->repository->save(${{ entityName|camel }});
            }
        } catch (Exception $e) {
            $messages[] = __('There was an error saving the data: ') . $e->getMessage();
            $error = true;
        }

        return $resultJson->setData([
            'messages' => $messages,
            'error' => $error
        ]);
    }
}
