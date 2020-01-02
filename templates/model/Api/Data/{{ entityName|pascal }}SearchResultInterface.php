<?php

/**
 * @copyright Copyright © {{ commentsYear }} {{ commentsCompanyName }}. All rights reserved.
 * @author    {{ commentsUserEmail }}
 */

namespace {{ vendorName|pascal }}\{{ moduleName|pascal }}\Api\Data;

use Magento\Framework\Api\SearchResultsInterface;

interface {{ entityName|pascal }}SearchResultInterface extends SearchResultsInterface
{
    /**
     * @return {{ entityName|pascal }}Interface[]
     */
    public function getItems();

    /**
     * @param {{ entityName|pascal }}Interface[] $items
     * @return $this
     */
    public function setItems(array $items);
}
