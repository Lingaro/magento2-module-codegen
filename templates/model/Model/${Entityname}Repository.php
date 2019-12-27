<?php

/**
 * @copyright Copyright © ${commentsYear} ${CommentsCompanyName}. All rights reserved.
 * @author    ${commentsUserEmail}
 */

namespace ${Vendorname}\${Modulename}\Model;

use ${Vendorname}\${Modulename}\Api\${Entityname}RepositoryInterface;
use Magento\Framework\Api\SearchCriteriaInterface;
use Magento\Framework\Api\SortOrder;
use Magento\Framework\Exception\NoSuchEntityException;
use ${Vendorname}\${Modulename}\Api\Data\${Entityname}Interface;
use ${Vendorname}\${Modulename}\Api\Data\${Entityname}SearchResultInterface;
use ${Vendorname}\${Modulename}\Api\Data\${Entityname}SearchResultInterfaceFactory;
use ${Vendorname}\${Modulename}\Model\ResourceModel\${Entityname}\CollectionFactory as ${entityname}CollectionFactory;
use ${Vendorname}\${Modulename}\Model\ResourceModel\${Entityname}\Collection;

class ${Entityname}Repository implements ${Entityname}RepositoryInterface
{

    /**
     * @var ${Entityname}Factory
     */
    private $${entityname}Factory;

    /**
     * @var ${Entityname}CollectionFactory
     */
    private $${entityname}CollectionFactory;

    /**
     * @var ${Entityname}SearchResultInterfaceFactory
     */
    private $searchResultFactory;

    public function __construct(
        ${Entityname}Factory $${entityname}Factory,
        ${Entityname}CollectionFactory $${entityname}CollectionFactory,
        ${Entityname}SearchResultInterfaceFactory $${entityname}SearchResultInterfaceFactory
    ) {
        $this->${entityname}Factory = $${entityname}Factory;
        $this->${entityname}CollectionFactory = $${entityname}CollectionFactory;
        $this->searchResultFactory = $${entityname}SearchResultInterfaceFactory;
    }

    public function getById($id)
    {
        $${entityname} = $this->${entityname}Factory->create();
        $${entityname}->getResource()->load($${entityname}, $id);
        if (!$${entityname}->getId()) {
            throw new NoSuchEntityException(__('Unable to find ${entityname} with ID "%1"', $id));
        }
        return $${entityname};
    }

    public function save(${Entityname}Interface $${entityname})
    {
        $${entityname}->getResource()->save($${entityname});
        return $${entityname};
    }

    public function delete(${Entityname}Interface $${entityname})
    {
        $${entityname}->getResource()->delete($${entityname});
    }

    public function getList(SearchCriteriaInterface $searchCriteria)
    {
        $collection = $this->${entityname}CollectionFactory->create();
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
        /** @var ${Entityname}SearchResultInterface $searchResults */
        $searchResults = $this->searchResultFactory->create();
        $searchResults->setSearchCriteria($searchCriteria);
        $searchResults->setItems($collection->getItems());
        $searchResults->setTotalCount($collection->getSize());
        return $searchResults;
    }
}
