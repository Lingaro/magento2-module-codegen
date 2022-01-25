{{ include(template_from_string(headerPHP)) }}
namespace {{ vendorName|pascal }}\{{ moduleName|pascal }}\Queue;

use {{ vendorName|pascal }}\{{ moduleName|pascal }}\Api\Data\{{ topic|pascal }}RequestInterface;

class {{ topic|pascal }}Handler
{
    public function process({{ topic|pascal }}RequestInterface $message): void
    {
        // Add your code here...
    }
}
