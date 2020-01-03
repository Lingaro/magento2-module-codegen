<?php

/**
 * @copyright Copyright © {{ commentsYear }} {{ commentsCompanyName }}. All rights reserved.
 * @author    {{ commentsUserEmail }}
 */

namespace {{ vendorName|pascal }}\{{ moduleName|pascal }}\Api\Data;

interface {{ entityName|pascal }}Interface
{
    /**
     * @return int|null
     */
    public function getId();

    /**
     * @param int $value
     * @return void
     */
    public function setId($value);
{% for item in fields %}

    /**
     * @return {% if item.database_type in [ 'int', 'smallint' ] %}int{% else %}string{% endif %}|null
     */
    public function get{{ item.name|pascal }}();

    /**
     * @param
     {%- if item.database_type in [ 'int', 'smallint' ] %} int {% else %} string {% endif %}$value
     * @return void
     */
    public function set{{ item.name|pascal }}({% if item.database_type in [ 'int', 'smallint' ] %}int {% else %}string {% endif %}$value);
{% endfor %}
}
