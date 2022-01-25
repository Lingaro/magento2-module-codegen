{{ include(template_from_string(headerPHP)) }}
namespace {{ vendorName|pascal }}\{{ moduleName|pascal }}\Model\ResourceModel;

use Magento\Framework\Model\ResourceModel\Db\AbstractDb;

class {{ entityName|pascal }} extends AbstractDb
{
    protected function _construct(): void
    {
        $this->_init('{{ vendorName|snake }}_{{ moduleName|snake }}_{{ entityName|snake }}', 'entity_id');
    }
}
