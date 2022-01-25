{{ include(template_from_string(headerPHP)) }}
namespace {{ vendorName|pascal }}\{{ moduleName|pascal }}\Block;

use Magento\Framework\View\Element\Template;

class {{ blockName|pascal }} extends Template
{
    // Write your methods here...
}
