{{ include(template_from_string(headerJS)) }}
define(['jquery'{% if type == 'jQuery widget' %}, 'jquery/ui'{% endif %}], function ($) {
    'use strict';

{% if type == 'jQuery widget' %}
    $.widget('{{ vendorName|camel }}.{{ name|camel }}', {
        options: {
            // default config options
        },
        _create: function () {
            // module initialization
        }
    });

    return $.{{ vendorName|camel }}.{{ name|camel }};
{% else %}
    return function (config, element) {
        // module initialization
    };
{% endif %}
});
