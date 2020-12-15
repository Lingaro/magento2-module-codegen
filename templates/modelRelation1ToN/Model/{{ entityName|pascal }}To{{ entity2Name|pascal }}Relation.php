<?php

/**
 * @copyright Copyright © {{ commentsYear }} {{ commentsCompanyName|raw }}. All rights reserved.
 * @author    {{ commentsUserEmail }}
 */

namespace {{ vendorName|pascal }}\{{ moduleName|pascal }}\Model;

use Magento\Framework\Api\FilterBuilder;
use Magento\Framework\Api\SearchCriteriaBuilder;
use Magento\Framework\Exception\NoSuchEntityException;
use {{ vendorName|pascal }}\{{ moduleName|pascal }}\Api\Data\{{ entityName|pascal }}Interface;
use {{ vendorName|pascal }}\{{ moduleName|pascal }}\Api\Data\{{ entity2Name|pascal }}Interface;
use {{ vendorName|pascal }}\{{ moduleName|pascal }}\Api\{{ entity2Name|pascal }}RepositoryInterface;
use {{ vendorName|pascal }}\{{ moduleName|pascal }}\Api\{{ entityName|pascal }}RepositoryInterface;
use {{ vendorName|pascal }}\{{ moduleName|pascal }}\Api\{{ entityName|pascal }}To{{ entity2Name|pascal }}RelationInterface;

class {{ entityName|pascal }}To{{ entity2Name|pascal }}Relation implements {{ entityName|pascal }}To{{ entity2Name|pascal }}RelationInterface
{
    /** @var {{ entity2Name|pascal }}RepositoryInterface */
    protected ${{ entity2Name|camel }}Repository;

    /**  @var {{ entityName|pascal }}RepositoryInterface */
    protected ${{ entityName|camel }}Repository;

    /** @var FilterBuilder */
    protected $filterBuilder;

    /** @var SearchCriteriaBuilder */
    protected $searchBuilder;

    /**
     * CountryToCityRepository constructor.
     * @param {{ entity2Name|pascal }}RepositoryInterface ${{ entity2Name|camel }}Repository
     * @param {{ entityName|pascal }}RepositoryInterface ${{ entityName|camel }}Repository
     * @param FilterBuilder $filterBuilder
     * @param SearchCriteriaBuilder $searchBuilder
     */
    public function __construct(
        {{ entity2Name|pascal }}RepositoryInterface ${{ entity2Name|camel }}Repository,
        {{ entityName|pascal }}RepositoryInterface ${{ entityName|camel }}Repository,
        FilterBuilder $filterBuilder,
        SearchCriteriaBuilder $searchBuilder
    ) {
        $this->{{ entity2Name|camel }}Repository = ${{ entity2Name|camel }}Repository;
        $this->{{ entityName|camel }}Repository = ${{ entityName|camel }}Repository;
        $this->filterBuilder = $filterBuilder;
        $this->searchBuilder = $searchBuilder;
    }

    /**
    * @param {{ entityName|pascal }}Interface ${{ entityName|camel }}
    * @return {{ entity2Name|pascal }}Interface[]
    */
    public function get{{ entity2Name|pascal|pluralize }}({{ entityName|pascal }}Interface ${{ entityName|camel }}): array
    {
        $this->searchBuilder->addFilters([
            $this->filterBuilder
                ->setField('{{ entityName|snake }}_id')
                ->setValue(${{ entityName|camel }}->getId())
                ->setConditionType('eq')
                ->create()
        ]);

        return $this->{{ entity2Name|camel }}Repository->getList($this->searchBuilder->create())->getItems();
    }

    /**
     * @param {{ entity2Name|pascal }}Interface ${{ entity2Name|camel }}
     * @return {{ entityName|pascal }}Interface|null
     */
    public function get{{ entityName|pascal }}({{ entity2Name|pascal }}Interface ${{ entity2Name|camel }}): ?{{ entityName|pascal }}Interface
    {
        try {
            $entityId = ${{ entity2Name|camel }}->get{{ entityName|pascal }}Id();
            if(empty($entityId)) {
                return null;
            }

            return $this->{{ entityName|camel }}Repository->getById($entityId);
        } catch (NoSuchEntityException $exception) {
            return null;
        }
    }

}
