define([
{% if type == 'jQuery widget' %}
    'jquery',
    'jquery/ui'
{% elseif (type == 'plain' and extendMethod) %}
    'mage/utils/wrapper'
{% endif %}
    ], function ({% if type == 'jQuery widget' %}${%elseif (type == 'plain' and extendMethod) %}wrapper{%endif%}) {
    'use strict';
{% if ("jQuery widget" == type) %}

{{ extendMethod ? "return function (originalWidget) {" : "" }}
    $.widget('{{ widgetName }}', originalWidget, {});

    return $.{{ widgetName }}
{{ extendMethod ? "};" : "" }}
{% elseif "component" == type %}

    return function (Component) {
        return Component.extend({
        {{ componentMethodName }}: function () {
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
