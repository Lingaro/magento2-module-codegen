<?php

/**
 * @copyright Copyright © {{ commentsYear }} {{ commentsCompanyName }}. All rights reserved.
 * @author    {{ commentsUserEmail }}
 */

namespace {{ vendorName|pascal }}\{{ moduleName|pascal }}\Api;

use Magento\Framework\Api\SearchCriteriaInterface;
use Magento\Framework\Exception\NoSuchEntityException;
use {{ vendorName|pascal }}\{{ moduleName|pascal }}\Api\Data\{{ entityName|pascal }}Interface;
use {{ vendorName|pascal }}\{{ moduleName|pascal }}\Api\Data\{{ entityName|pascal }}SearchResultInterface;

interface {{ entityName|pascal }}RepositoryInterface
{
    /**
     * @param int $id
     * @return {{ entityName|pascal }}Interface
     * @throws NoSuchEntityException
     */
    public function getById($id): {{ entityName|pascal }}Interface;

    /**
     * @param {{ entityName|pascal }}Interface ${{ entityName|pascal }}
     * @return void
     */
    public function save({{ entityName|pascal }}Interface ${{ entityName|pascal }}): void;

    /**
     * @param {{ entityName|pascal }}Interface ${{ entityName|pascal }}
     * @return void
     */
    public function delete({{ entityName|pascal }}Interface ${{ entityName|pascal }}): void;

    /**
     * @param SearchCriteriaInterface $searchCriteria
     * @return {{ entityName|pascal }}SearchResultInterface
     */
    public function getList(SearchCriteriaInterface $searchCriteria): {{ entityName|pascal }}SearchResultInterface;
}
