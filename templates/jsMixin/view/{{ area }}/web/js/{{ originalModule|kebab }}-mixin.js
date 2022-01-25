{{ include(template_from_string(headerJS)) }}
define([
{% if type == 'jQuery widget' %}
    'jquery',
    'jquery/ui'
{% elseif type == 'js object' or type == 'js function' %}
    'mage/utils/wrapper'
{% endif %}
], function ({% if type == 'jQuery widget' %}${%elseif type == 'js object' or type == 'js function' %}wrapper{% endif %}) {
    'use strict';

{% if type == 'jQuery widget' or type == 'component' %}
    var mixin = {
        // Put your overrides here.
        // Use this._super() to call parent function.
    };

{% if type == 'jQuery widget' %}
    return function (targetWidget) {
        $.widget('{{ widgetName }}', targetWidget, mixin);
        return $.{{ widgetName }};
    }
{% elseif type == 'component' %}
    return function (targetComponent) {
        return targetComponent.extend(mixin);
    }
{% endif %}
{% elseif type == 'js function' %}
    return function (targetFunction) {
        return wrapper.wrap(targetFunction, function (originalFunction, ...args) {
            // Put your overrides here.
            // Use originalFunction(...args) to call parent function.
            // You can of course change ...args to real arguments.
        });
    };
{% elseif type == 'js object' %}
    return function (targetObject) {
        targetObject.{{ methodName }} = wrapper.wrapSuper(targetObject.{{ methodName }}, function (...args) {
            // Put your overrides here.
            // Use this._super(...args) to call parent function.
            // You can of course change ...args to real arguments.
        });

        return targetObject;
    };
{% endif %}
});
