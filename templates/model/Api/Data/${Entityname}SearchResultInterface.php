<?php

/**
 * @copyright Copyright © ${commentsYear} ${CommentsCompanyName}. All rights reserved.
 * @author    ${commentsUserEmail}
 */

namespace ${Vendorname}\${Modulename}\Api\Data;

use Magento\Framework\Api\SearchResultsInterface;

interface ${Entityname}SearchResultInterface extends SearchResultsInterface
{
    /**
     * @return \${Vendorname}\${Modulename}\Api\Data\${Entityname}Interface[]
     */
    public function getItems();

    /**
     * @param \${Vendorname}\${Modulename}\Api\Data\${Entityname}Interface[] $items
     * @return $this
     */
    public function setItems(array $items);
}
