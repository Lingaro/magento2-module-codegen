{{ include(template_from_string(headerPHP)) }}
namespace {{ vendorName|pascal }}\{{ moduleName|pascal }}\Api;

use Magento\Framework\Api\SearchCriteriaInterface;
use Magento\Framework\Exception\NoSuchEntityException;
use {{ vendorName|pascal }}\{{ moduleName|pascal }}\Api\Data\{{ entityName|pascal }}Interface;
use {{ vendorName|pascal }}\{{ moduleName|pascal }}\Api\Data\{{ entityName|pascal }}SearchResultInterface;

interface {{ entityName|pascal }}RepositoryInterface
{
    /**
     * @param int $id
     * @return \{{ vendorName|pascal }}\{{ moduleName|pascal }}\Api\Data\{{ entityName|pascal }}Interface
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function getById(int $id): {{ entityName|pascal }}Interface;

    /**
     * @param \{{ vendorName|pascal }}\{{ moduleName|pascal }}\Api\Data\{{ entityName|pascal }}Interface
     * @return void
     */
    public function save({{ entityName|pascal }}Interface ${{ entityName|pascal }}): void;

    /**
     * @param \{{ vendorName|pascal }}\{{ moduleName|pascal }}\Api\Data\{{ entityName|pascal }}Interface
     * @return void
     */
    public function delete({{ entityName|pascal }}Interface ${{ entityName|pascal }}): void;

    /**
     * @param \Magento\Framework\Api\SearchCriteriaInterface $searchCriteria
     * @return \{{ vendorName|pascal }}\{{ moduleName|pascal }}\Api\Data\{{ entityName|pascal }}SearchResultInterface
     */
    public function getList(SearchCriteriaInterface $searchCriteria): {{ entityName|pascal }}SearchResultInterface;
}
