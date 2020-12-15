<?php

/**
 * @copyright Copyright © {{ commentsYear }} {{ commentsCompanyName|raw }}. All rights reserved.
 * @author    {{ commentsUserEmail }}
 */

namespace {{ vendorName|pascal }}\{{ moduleName|pascal }}\Api\Data;

interface {{ entity2Name|pascal }}Interface
{
    /**
     * @return int|null
     */
    public function get{{ entityName|pascal }}Id();

    /**
     * @param int $value
     * @return void
     */
    public function set{{ entityName|pascal }}Id(int $value);

}
