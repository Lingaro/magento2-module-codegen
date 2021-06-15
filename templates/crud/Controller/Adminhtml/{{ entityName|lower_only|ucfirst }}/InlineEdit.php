<?php

/**
 * @copyright Copyright © {{ commentsYear }} {{ commentsCompanyName|raw }}. All rights reserved.
 * @author    {{ commentsUserEmail }}
 */

declare(strict_types=1);

namespace {{ vendorName|pascal }}\{{ moduleName|pascal }}\Controller\Adminhtml\{{ entityName|lower_only|ucfirst }};

use Exception;
use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Framework\App\Action\HttpPostActionInterface;
use Magento\Framework\Controller\Result\Json;
use Magento\Framework\Controller\Result\JsonFactory;
use {{ vendorName|pascal }}\{{ moduleName|pascal }}\Api\{{ entityName|pascal }}RepositoryInterface;
use {{ vendorName|pascal }}\{{ moduleName|pascal }}\Model\{{ entityName|pascal }};

/**
 * @SuppressWarnings(PHPMD.CouplingBetweenObjects)
 */
class InlineEdit extends Action implements HttpPostActionInterface
{
    public const ADMIN_RESOURCE = '{{ vendorName|pascal }}_{{ moduleName|pascal }}::{{ entityName|snake }}';

    private JsonFactory $jsonFactory;
    private {{ entityName|pascal }}RepositoryInterface $repository;

    public function __construct(
        Context $context,
        JsonFactory $jsonFactory,
        {{ entityName|pascal }}RepositoryInterface $repository
    ) {
        parent::__construct($context);
        $this->jsonFactory = $jsonFactory;
        $this->repository = $repository;
    }

    public function execute(): Json
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
                /** @var {{ entityName|pascal }} ${{ entityName|camel }} */
                ${{ entityName|camel }} = $this->repository->getById(${{ entityName|camel }}Id);
                foreach ($postItems[${{ entityName|camel }}Id] as $key => $value) {
                    ${{ entityName|camel }}->setData($key, $value);
                }
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
