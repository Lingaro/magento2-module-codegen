{{ include(template_from_string(headerPHP)) }}
namespace {{ vendorName|pascal }}\{{ moduleName|pascal }}\Plugin;

use Magento\Sales\Api\Data\OrderInterface;
use Magento\Sales\Api\OrderRepositoryInterface;
use Magento\Sales\Api\Data\OrderSearchResultInterface;

class SetOrderExtensionAttributes
{
    /**
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function afterGet(OrderRepositoryInterface $subject, OrderInterface $order): OrderInterface
    {
        return $this->addExtensionAttributes($order);
    }

    /**
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function afterGetList(
        OrderRepositoryInterface $subject,
        OrderSearchResultInterface $searchCriteria
    ): OrderSearchResultInterface {
        foreach ($searchCriteria->getItems() as $entity) {
            $this->addExtensionAttributes($entity);
        }
        return $searchCriteria;
    }

    /**
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function beforeSave(OrderRepositoryInterface $subject, OrderInterface $order): void
    {
        $extensionAttributes = $order->getExtensionAttributes();

{% for field in fields %}
        $order->setData('{{ field.name|snake }}', $extensionAttributes->get{{ field.name|pascal }}());
{% endfor %}
    }

    private function addExtensionAttributes(OrderInterface $order): OrderInterface
    {
        $extensionAttributes = $order->getExtensionAttributes();

{% for field in fields %}
        if (!$extensionAttributes->get{{ field.name|pascal }}()) {
            $extensionAttributes->set{{ field.name|pascal }}($order->getData('{{ field.name|snake }}'));
        }
{% endfor %}

        return $order;
    }
}
