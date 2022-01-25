{{ include(template_from_string(headerPHP)) }}
namespace {{ vendorName|pascal }}\{{ moduleName|pascal }}\ViewModel;

use Magento\Framework\View\Element\Block\ArgumentInterface;

class {{ viewModelName|pascal }} implements ArgumentInterface
{
    // Write your methods here...
}
