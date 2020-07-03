define([
{% if type == 'jQuery widget' %}
    'jquery',
    'jquery/ui'
{% elseif (type == 'plain' and extendMethod) %}
    'mage/utils/wrapper'
{% endif %}
    ], function ({% if type == 'jQuery widget' %}${%elseif (type == 'plain' and extendMethod) %}wrapper, {{methodName}}{%endif%}) {
    'use strict';
{% if ("jQuery widget" == type) %}

{{ extendMethod ? "return function (originalWidget) {" : "" }}
    $.widget('{{ methodName }}', originalWidget, {});

    return $.{{ methodName }}
{{ extendMethod ? "};" : "" }}
{% elseif "component" == type %}

    return function (Component) {
        return Component.extend({
        {{ methodName }}: function () {
            {{ extendMethod ? "this._super();" : "" }}
            }
        });
    }
{% else %}

    return function ({{ methodName }}) {
{% if extendMethod %}
        return wrapper.wrap({{ methodName }}, function (original, config, element) {
            original(config, element);
        });
{% endif %}
    };
{% endif %}
});
