<?php

/**
 * @copyright Copyright © ${commentsYear} ${CommentsCompanyName}. All rights reserved.
 * @author    ${commentsUserEmail}
 */

namespace ${Vendorname}\${Modulename}\Api;

use Magento\Framework\Api\SearchCriteriaInterface;
use ${Vendorname}\${Modulename}\Api\Data\${Entityname}Interface;

interface ${Entityname}RepositoryInterface
{
    /**
     * Get ${Entityname} by ${Entityname} id
     * @param int $id
     * @return \${Vendorname}\${Modulename}\Api\Data\${Entityname}Interface
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function getById($id);

    /**
     * Create or update ${Entityname}
     * @param \${Vendorname}\${Modulename}\Api\Data\${Entityname}Interface $${Entityname}
     * @return \${Vendorname}\${Modulename}\Api\Data\${Entityname}Interface
     */
    public function save(${Entityname}Interface $${Entityname});

    /**
     * Delete ${Entityname}
     * @param \${Vendorname}\${Modulename}\Api\Data\${Entityname}Interface $${Entityname}
     * @return void
     */
    public function delete(${Entityname}Interface $${Entityname});

    /**
     * @param \Magento\Framework\Api\SearchCriteriaInterface $searchCriteria
     * @return \${Vendorname}\${Modulename}\Api\Data\${Entityname}SearchResultInterface
     */
    public function getList(SearchCriteriaInterface $searchCriteria);
}
