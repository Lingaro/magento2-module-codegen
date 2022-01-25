{{ include(template_from_string(headerPHP)) }}
namespace {{ vendorName|pascal }}\{{ moduleName|pascal }}\Observer;

use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;

class {{ observerName|pascal }} implements ObserverInterface
{
    public function execute(Observer $observer): void
    {
        // Observer execution code...
    }
}
