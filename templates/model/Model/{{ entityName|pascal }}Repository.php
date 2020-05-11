<?php

/**
 * @copyright Copyright © {{ commentsYear }} {{ commentsCompanyName }}. All rights reserved.
 * @author    {{ commentsUserEmail }}
 */

namespace {{ vendorName|pascal }}\{{ moduleName|pascal }}\Model;

use {{ vendorName|pascal }}\{{ moduleName|pascal }}\Api\{{ entityName|pascal }}RepositoryInterface;
use Exception;
use Magento\Framework\Api\SearchCriteriaInterface;
use Magento\Framework\Api\SortOrder;
use Magento\Framework\Exception\AlreadyExistsException;
use Magento\Framework\Exception\NoSuchEntityException;
use {{ vendorName|pascal }}\{{ moduleName|pascal }}\Api\Data\{{ entityName|pascal }}Interface;
use {{ vendorName|pascal }}\{{ moduleName|pascal }}\Api\Data\{{ entityName|pascal }}SearchResultInterface;
use {{ vendorName|pascal }}\{{ moduleName|pascal }}\Api\Data\{{ entityName|pascal }}SearchResultInterfaceFactory;
use {{ vendorName|pascal }}\{{ moduleName|pascal }}\Model\ResourceModel\{{ entityName|pascal }}\CollectionFactory as {{ entityName|pascal }}CollectionFactory;
use {{ vendorName|pascal }}\{{ moduleName|pascal }}\Model\ResourceModel\{{ entityName|pascal }}\Collection;

class {{ entityName|pascal }}Repository implements {{ entityName|pascal }}RepositoryInterface
{

    /**
     * @var {{ entityName|pascal }}Factory
     */
    private ${{ entityName|camel }}Factory;

    /**
     * @var {{ entityName|pascal }}CollectionFactory
     */
    private ${{ entityName|camel }}CollectionFactory;

    /**
     * @var {{ entityName|pascal }}SearchResultInterfaceFactory
     */
    private $searchResultFactory;

    public function __construct(
        {{ entityName|pascal }}Factory ${{ entityName|camel }}Factory,
        {{ entityName|pascal }}CollectionFactory ${{ entityName|camel }}CollectionFactory,
        {{ entityName|pascal }}SearchResultInterfaceFactory ${{ entityName|camel }}SearchResultInterfaceFactory
    ) {
        $this->{{ entityName|camel }}Factory = ${{ entityName|camel }}Factory;
        $this->{{ entityName|camel }}CollectionFactory = ${{ entityName|camel }}CollectionFactory;
        $this->searchResultFactory = ${{ entityName|camel }}SearchResultInterfaceFactory;
    }

    /**
     * @inheritDoc
     */
    public function getById($id)
    {
        ${{ entityName|camel }} = $this->{{ entityName|camel }}Factory->create();
        ${{ entityName|camel }}->getResource()->load(${{ entityName|camel }}, $id);
        if (!${{ entityName|camel }}->getId()) {
            throw new NoSuchEntityException(__('Unable to find {{ entityName|pascal }} with ID "%1"', $id));
        }
        return ${{ entityName|camel }};
    }

    /**
     * @inheritDoc
     * @throws AlreadyExistsException
     */
    public function save({{ entityName|pascal }}Interface ${{ entityName|camel }})
    {
        /** @var ${{ entityName|camel }} {{ entityName|pascal }} **/
        ${{ entityName|camel }}->getResource()->save(${{ entityName|camel }});
    }

    /**
     * @inheritDoc
     * @throws Exception
     */
    public function delete({{ entityName|pascal }}Interface ${{ entityName|camel }})
    {
        /** @var ${{ entityName|camel }} {{ entityName|pascal }} **/
        ${{ entityName|camel }}->getResource()->delete(${{ entityName|camel }});
    }

    /**
     * @inheritDoc
     */
    public function getList(SearchCriteriaInterface $searchCriteria)
    {
        $collection = $this->{{ entityName|camel }}CollectionFactory->create();
        $this->addFiltersToCollection($searchCriteria, $collection);
        $this->addSortOrdersToCollection($searchCriteria, $collection);
        $this->addPagingToCollection($searchCriteria, $collection);
        $collection->load();
        return $this->buildSearchResult($searchCriteria, $collection);
    }

    private function addFiltersToCollection(SearchCriteriaInterface $searchCriteria, Collection $collection)
    {
        foreach ($searchCriteria->getFilterGroups() as $filterGroup) {
            $fields = $conditions = [];
            foreach ($filterGroup->getFilters() as $filter) {
                $fields[] = $filter->getField();
                $conditions[] = [$filter->getConditionType() => $filter->getValue()];
            }
            $collection->addFieldToFilter($fields, $conditions);
        }
    }

    private function addSortOrdersToCollection(SearchCriteriaInterface $searchCriteria, Collection $collection)
    {
        foreach ((array) $searchCriteria->getSortOrders() as $sortOrder) {
            $direction = $sortOrder->getDirection() == SortOrder::SORT_ASC ? 'asc' : 'desc';
            $collection->addOrder($sortOrder->getField(), $direction);
        }
    }

    private function addPagingToCollection(SearchCriteriaInterface $searchCriteria, Collection $collection)
    {
        $collection->setPageSize($searchCriteria->getPageSize());
        $collection->setCurPage($searchCriteria->getCurrentPage());
    }

    private function buildSearchResult(SearchCriteriaInterface $searchCriteria, Collection $collection)
    {
        /** @var {{ entityName|pascal }}SearchResultInterface $searchResults */
        $searchResults = $this->searchResultFactory->create();
        $searchResults->setSearchCriteria($searchCriteria);
        $searchResults->setItems($collection->getItems());
        $searchResults->setTotalCount($collection->getSize());
        return $searchResults;
    }
}
