{% if withBlock %}{{ include(template_from_string(headerPHP)) }}
namespace {{ vendorName|pascal }}\{{ moduleName|pascal }}\ViewModel;

use Magento\Framework\View\Element\Block\ArgumentInterface;
use Magento\Framework\Serialize\Serializer\Json;

class {{ name|pascal }} implements ArgumentInterface
{
    private Json $serializer;

    public function __construct(Json $serializer)
    {
        $this->serializer = $serializer;
    }

    public function getJsonConfig(): string
    {
        return $this->serializer->serialize([
            '{{ name|camel }}' => []
        ]);
    }
}
{% endif %}
