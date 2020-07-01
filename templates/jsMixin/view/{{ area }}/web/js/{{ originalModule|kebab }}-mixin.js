define({{ ("jQuery widget" == type) ? "['jquery']," : "" }}function ($) {
    'use strict';

    {{ methodName }}: function() {

    };

{% if extendMethod %}
    return function (targetWidget) {
        $.widget('mage.modal', targetWidget, methodName);

        return $.mage.modal;
    };
{% endif %}
});
