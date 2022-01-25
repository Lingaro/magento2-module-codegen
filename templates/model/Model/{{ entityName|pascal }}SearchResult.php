{{ include(template_from_string(headerPHP)) }}
namespace {{ vendorName|pascal }}\{{ moduleName|pascal }}\Model;

use Magento\Framework\Api\SearchResults;
use {{ vendorName|pascal }}\{{ moduleName|pascal }}\Api\Data\{{ entityName|pascal }}SearchResultInterface;

class {{ entityName|pascal }}SearchResult extends SearchResults implements {{ entityName|pascal }}SearchResultInterface
{

}
