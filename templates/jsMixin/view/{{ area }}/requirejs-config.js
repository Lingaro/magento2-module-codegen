{{ include(template_from_string(headerJS)) }}
var config = {
    "config": {
        "mixins": {
            "{{ originalModule }}": {
                "{{ vendorName|pascal }}_{{ moduleName|pascal }}/js/{{ originalModule|kebab }}-mixin": true
            }
        }
    }
}
