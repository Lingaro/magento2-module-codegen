<?php
/**
 * @copyright Copyright © {{ commentsYear }} {{ commentsCompanyName }}. All rights reserved.
 * @author    {{ commentsUserEmail }}
 */

namespace {{ vendorName|pascal }}\{{ moduleName|pascal }}\Plugin;

use Magento\Framework\Api\SearchCriteriaInterface;
use Magento\Sales\Api\Data\OrderInterface;
use Magento\Sales\Api\OrderRepositoryInterface;

class SetOrderExtensionAttributes
{

    /**
     * @param OrderRepositoryInterface $subject
     * @param OrderInterface $order
     * @return OrderInterface
     */
    public function afterGet(
        OrderRepositoryInterface $subject,
        OrderInterface $order
    ): OrderInterface {
        return $this->addExtensionAttributes($order);
    }

    /**
     * @param OrderRepositoryInterface $subject
     * @param SearchCriteriaInterface $searchCriteria
     * @return SearchCriteriaInterface
     */
    public function afterGetList(
        OrderRepositoryInterface $subject,
        SearchCriteriaInterface $searchCriteria
    ): SearchCriteriaInterface {
        foreach ($searchCriteria->getItems() as $entity) {
            $this->addExtensionAttributes($entity);
        }
        return $searchCriteria;
    }

    /**
     * @param OrderRepositoryInterface $subject
     * @param OrderInterface $order
     */
    public function beforeSave(
        OrderRepositoryInterface $subject,
        OrderInterface $order
    ) {

        $extensionAttributes = $order->getExtensionAttributes();

{% for field in fields %}
        $order->setData('{{ field.name|snake }}', $extensionAttributes->get{{ field.name|pascal }}());
{% endfor %}
    }

    /**
     * @param OrderInterface $order
     * @return OrderInterface
     */
    private function addExtensionAttributes(OrderInterface $order): OrderInterface
    {
        $extensionAttributes = $order->getExtensionAttributes();

{% for field in fields %}
        $extensionAttributes->set{{ field.name|pascal }}($order->getData('{{ field.name|snake }}'));
{% endfor %}

        return $order;
    }
}
