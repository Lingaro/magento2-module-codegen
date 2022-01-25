{{ include(template_from_string(headerPHP)) }}
namespace {{ vendorName|pascal }}\{{ moduleName|pascal }}\Model;

use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Store\Model\ScopeInterface;

class Config
{
    public const XML_PATH_{{ sectionId|snake|upper }}_{{ groupId|snake|upper }}_{{ id|snake|upper }} = '{{ sectionId|snake }}/{{ groupId|snake }}/{{ id|snake }}';

    private ScopeConfigInterface $scopeConfig;

    public function __construct(ScopeConfigInterface $scopeConfig)
    {
        $this->scopeConfig = $scopeConfig;
    }

    public function get{{ id|pascal }}({% if configScope != 'default' %}${{ configScope }}Id = null{% endif %}): ?string
    {
        return $this->scopeConfig->getValue(
            static::XML_PATH_{{ sectionId|snake|upper }}_{{ groupId|snake|upper }}_{{ id|snake|upper }},
{% if configScope == 'default' %}
            ScopeConfigInterface::SCOPE_TYPE_DEFAULT
{% else %}
            ScopeInterface::SCOPE_{{ configScope|upper }},
            ${{ configScope }}Id
{% endif %}
        );
    }
}
