{{ include(template_from_string(headerPHP)) }}
namespace {{ vendorName|pascal }}\{{ moduleName|pascal }}\Model;

use Magento\Framework\Model\AbstractModel;
use {{ vendorName|pascal }}\{{ moduleName|pascal }}\Api\Data\{{ entityName|pascal }}Interface;
use {{ vendorName|pascal }}\{{ moduleName|pascal }}\Model\ResourceModel\{{ entityName|pascal }} as {{ entityName|pascal }}ResourceModel;

class {{ entityName|pascal }} extends AbstractModel implements {{ entityName|pascal }}Interface
{
    protected $_eventPrefix = '{{ vendorName|snake }}_{{ moduleName|snake }}_{{ entityName|snake }}';

    protected function _construct(): void
    {
        $this->_init({{ entityName|pascal }}ResourceModel::class);
    }

    public function getId()
    {
        return $this->getData('entity_id');
    }

    public function setId($value): void
    {
        $this->setData('entity_id', $value);
    }
{% for item in fields %}
{% if item.name|lower_only != 'id' %}

    public function get{{ item.name|pascal }}(): ?{{ databaseTypeToPHP(item.databaseType) }}
    {
        return $this->getData('{{ item.name|snake }}');
    }

    public function set{{ item.name|pascal }}({{ databaseTypeToPHP(item.databaseType) }} $value): void
    {
        $this->setData('{{ item.name|snake }}', $value);
    }
{% endif %}
{% endfor %}
}
