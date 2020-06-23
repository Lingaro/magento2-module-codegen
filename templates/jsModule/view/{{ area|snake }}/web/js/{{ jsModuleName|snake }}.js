define([
    'jquery'{% if moduleType == 'widget' %},{% endif %}
    {% if moduleType == 'widget' %}'jquery/ui'{% endif %}
], function ($) {
    'use strict';
    {% if moduleType == 'widget' %}
    $.widget('vendor.{{ widgetName|camel }}', {
        options: {
            "myVar1": "defaultValue"
        },
        _create: function () {
            this._bind();
            // rest of code goes here
        },
        _bind: function() {
            // rest of code goes here
        }
    });

    return $.vendor.myWidget;
    {% else %}
    return function (config, element) {
        // your code goes here
    };
    {% endif %}
});
