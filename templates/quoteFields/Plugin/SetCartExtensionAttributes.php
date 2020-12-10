<?php
/**
 * @copyright Copyright © {{ commentsYear }} {{ commentsCompanyName }}. All rights reserved.
 * @author    {{ commentsUserEmail }}
 */

namespace {{ vendorName|pascal }}\{{ moduleName|pascal }}\Plugin;

use Magento\Quote\Api\CartRepositoryInterface;
use Magento\Quote\Api\Data\CartInterface;
use Magento\Quote\Api\Data\CartSearchResultsInterface;

class SetCartExtensionAttributes
{

    /**
     * @param CartRepositoryInterface $subject
     * @param CartInterface $quote
     * @return CartInterface
     */
    public function afterGet(
        CartRepositoryInterface $subject,
        CartInterface $quote
    ): CartInterface {
        return $this->addExtensionAttributes($quote);
    }

    /**
     * @param CartRepositoryInterface $subject
     * @param CartInterface $quote
     * @return CartInterface
     */
    public function afterGetForCustomer(
        CartRepositoryInterface $subject,
        CartInterface $quote
    ): CartInterface {
        return $this->addExtensionAttributes($quote);
    }

    /**
     * @param CartRepositoryInterface $subject
     * @param CartSearchResultsInterface $searchCriteria
     * @return CartSearchResultsInterface
     */
    public function afterGetList(
        CartRepositoryInterface $subject,
        CartSearchResultsInterface $searchCriteria
    ): CartSearchResultsInterface {
        foreach ($searchCriteria->getItems() as $entity) {
            $this->addExtensionAttributes($entity);
        }
        return $searchCriteria;
    }

    /**
     * @param CartRepositoryInterface $subject
     * @param CartInterface $quote
     */
    public function beforeSave(CartRepositoryInterface $subject, CartInterface $quote)
    {
        $extensionAttributes = $quote->getExtensionAttributes();

{% for field in fields %}
        $quote->setData('{{ field.name|snake }}', $extensionAttributes->get{{ field.name|pascal }}());
{% endfor %}
    }

    /**
     * @param CartInterface $quote
     * @return CartInterface
     */
    private function addExtensionAttributes(CartInterface $quote): CartInterface
    {
        $extensionAttributes = $quote->getExtensionAttributes();

{% for field in fields %}
        if (!$extensionAttributes->get{{ field.name|pascal }}()) {
            $extensionAttributes->set{{ field.name|pascal }}($quote->getData('{{ field.name|snake }}'));
        }
{% endfor %}

        return $quote;
    }
}
