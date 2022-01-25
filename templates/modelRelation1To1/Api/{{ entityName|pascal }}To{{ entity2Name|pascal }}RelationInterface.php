{{ include(template_from_string(headerPHP)) }}
namespace {{ vendorName|pascal }}\{{ moduleName|pascal }}\Api;

use {{ vendorName|pascal }}\{{ moduleName|pascal }}\Api\Data\{{ entityName|pascal }}Interface;
use {{ vendorName|pascal }}\{{ moduleName|pascal }}\Api\Data\{{ entity2Name|pascal }}Interface;

interface {{ entityName|pascal }}To{{ entity2Name|pascal }}RelationInterface
{
    /**
    * @param {{ entityName|pascal }}Interface ${{ entityName|camel }}
    * @return {{ entity2Name|pascal }}Interface|null
    */
    public function get{{ entity2Name|pascal }}({{ entityName|pascal }}Interface ${{ entityName|camel }}): ?{{ entity2Name|pascal }}Interface;

    /**
     * @param {{ entity2Name|pascal }}Interface ${{ entity2Name|camel }}
     * @return {{ entityName|pascal }}Interface|null
     */
    public function get{{ entityName|pascal }}({{ entity2Name|pascal }}Interface ${{ entity2Name|camel }}): ?{{ entityName|pascal }}Interface;

}
