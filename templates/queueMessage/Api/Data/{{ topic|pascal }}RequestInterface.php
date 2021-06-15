<?php

/**
 * @copyright Copyright © {{ commentsYear }} {{ commentsCompanyName|raw }}. All rights reserved.
 * @author    {{ commentsUserEmail }}
 */

declare(strict_types=1);

namespace {{ vendorName|pascal }}\{{ moduleName|pascal }}\Api\Data;

interface {{ topic|pascal }}RequestInterface
{
{% for item in requestFields %}

    /**
     * @return {{ item.type }}|null
     */
    public function get{{ item.name|pascal }}(): ?{{ item.type }};

    /**
     * @param {{ item.type }} $value
     * @return void
     */
    public function set{{ item.name|pascal }}({{ item.type }} $value): void;
{% endfor %}
}
