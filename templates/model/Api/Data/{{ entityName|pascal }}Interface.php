<?php

/**
 * @copyright Copyright © {{ commentsYear }} {{ commentsCompanyName }}. All rights reserved.
 * @author    {{ commentsUserEmail }}
 */

namespace {{ vendorName|pascal }}\{{ moduleName|pascal }}\Api\Data;

interface {{ entityName|pascal }}Interface
{
{%- for item in fields %}

    /**
     * @return {{ item.php_type }}|null
     */
    public function get{{ item.name|pascal }}(): ?{{ item.php_type }};

    /**
     * @param {{ item.php_type }} $value
     * @return void
     */
    public function set{{ item.name|pascal }}({{ item.php_type }} $value): void;
{% endfor %}
}
