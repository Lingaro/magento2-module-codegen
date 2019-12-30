<?php

/**
 * @copyright Copyright © {{ commentsYear }} {{ commentsCompanyName }}. All rights reserved.
 * @author    {{ commentsUserEmail }}
 */

namespace {{ vendorName|pascal }}\{{ moduleName|pascal }}\Api;

use Magento\Framework\Api\SearchCriteriaInterface;
use {{ vendorName|pascal }}\{{ moduleName|pascal }}\Api\Data\{{ entityName|pascal }}Interface;

interface {{ entityName|pascal }}RepositoryInterface
{
    /**
     * Get {{ entityName|pascal }} by {{ entityName|pascal }} id
     * @param int $id
     * @return \{{ vendorName|pascal }}\{{ moduleName|pascal }}\Api\Data\{{ entityName|pascal }}Interface
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function getById($id);

    /**
     * Create or update {{ entityName|pascal }}
     * @param \{{ vendorName|pascal }}\{{ moduleName|pascal }}\Api\Data\{{ entityName|pascal }}Interface ${{ entityName|pascal }}
     * @return \{{ vendorName|pascal }}\{{ moduleName|pascal }}\Api\Data\{{ entityName|pascal }}Interface
     */
    public function save({{ entityName|pascal }}Interface ${{ entityName|pascal }});

    /**
     * Delete {{ entityName|pascal }}
     * @param \{{ vendorName|pascal }}\{{ moduleName|pascal }}\Api\Data\{{ entityName|pascal }}Interface ${{ entityName|pascal }}
     * @return void
     */
    public function delete({{ entityName|pascal }}Interface ${{ entityName|pascal }});

    /**
     * @param \Magento\Framework\Api\SearchCriteriaInterface $searchCriteria
     * @return \{{ vendorName|pascal }}\{{ moduleName|pascal }}\Api\Data\{{ entityName|pascal }}SearchResultInterface
     */
    public function getList(SearchCriteriaInterface $searchCriteria);
}
