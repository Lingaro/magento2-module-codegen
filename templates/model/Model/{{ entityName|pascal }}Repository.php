{{ include(template_from_string(headerPHP)) }}
namespace {{ vendorName|pascal }}\{{ moduleName|pascal }}\Model;

use {{ vendorName|pascal }}\{{ moduleName|pascal }}\Api\{{ entityName|pascal }}RepositoryInterface;
use Magento\Framework\Api\SearchCriteriaInterface;
use Magento\Framework\Api\SearchCriteria\CollectionProcessorInterface;
use Magento\Framework\Exception\NoSuchEntityException;
use {{ vendorName|pascal }}\{{ moduleName|pascal }}\Api\Data\{{ entityName|pascal }}Interface;
use {{ vendorName|pascal }}\{{ moduleName|pascal }}\Api\Data\{{ entityName|pascal }}SearchResultInterface;
use {{ vendorName|pascal }}\{{ moduleName|pascal }}\Api\Data\{{ entityName|pascal }}SearchResultInterfaceFactory;
use {{ vendorName|pascal }}\{{ moduleName|pascal }}\Model\ResourceModel\{{ entityName|pascal }}\CollectionFactory as {{ entityName|pascal }}CollectionFactory;

class {{ entityName|pascal }}Repository implements {{ entityName|pascal }}RepositoryInterface
{
    private {{ entityName|pascal }}Factory ${{ entityName|camel }}Factory;
    private {{ entityName|pascal }}CollectionFactory ${{ entityName|camel }}CollectionFactory;
    private {{ entityName|pascal }}SearchResultInterfaceFactory $searchResultFactory;
    private CollectionProcessorInterface $collectionProcessor;

    public function __construct(
        {{ entityName|pascal }}Factory ${{ entityName|camel }}Factory,
        {{ entityName|pascal }}CollectionFactory ${{ entityName|camel }}CollectionFactory,
        {{ entityName|pascal }}SearchResultInterfaceFactory ${{ entityName|camel }}SearchResultInterfaceFactory,
        CollectionProcessorInterface $collectionProcessor
    ) {
        $this->{{ entityName|camel }}Factory = ${{ entityName|camel }}Factory;
        $this->{{ entityName|camel }}CollectionFactory = ${{ entityName|camel }}CollectionFactory;
        $this->searchResultFactory = ${{ entityName|camel }}SearchResultInterfaceFactory;
        $this->collectionProcessor = $collectionProcessor;
    }

    public function getById(int $id): {{ entityName|pascal }}Interface
    {
        ${{ entityName|camel }} = $this->{{ entityName|camel }}Factory->create();
        ${{ entityName|camel }}->getResource()->load(${{ entityName|camel }}, $id);
        if (!${{ entityName|camel }}->getId()) {
            throw new NoSuchEntityException(__('Unable to find {{ entityName|pascal }} with ID "%1"', $id));
        }
        return ${{ entityName|camel }};
    }

    public function save({{ entityName|pascal }}Interface ${{ entityName|camel }}): void
    {
        /** @var ${{ entityName|camel }} {{ entityName|pascal }} **/
        ${{ entityName|camel }}->getResource()->save(${{ entityName|camel }});
    }

    public function delete({{ entityName|pascal }}Interface ${{ entityName|camel }}): void
    {
        /** @var ${{ entityName|camel }} {{ entityName|pascal }} **/
        ${{ entityName|camel }}->getResource()->delete(${{ entityName|camel }});
    }

    public function getList(SearchCriteriaInterface $searchCriteria): {{ entityName|pascal }}SearchResultInterface
    {
        $collection = $this->{{ entityName|camel }}CollectionFactory->create();
        $this->collectionProcessor->process($searchCriteria, $collection);
        $searchResult = $this->searchResultFactory->create();
        $searchResult->setSearchCriteria($searchCriteria);
        $searchResult->setItems($collection->getItems());
        $searchResult->setTotalCount($collection->getSize());
        return $searchResult;
    }
}
