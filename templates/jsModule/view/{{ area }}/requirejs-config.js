{{ include(template_from_string(headerJS)) }}
var config = {
    "map": {
        "*": {
            "{{ name|camel }}": "{{ vendorName|pascal }}_{{ moduleName|pascal }}/js/{{ name|snake }}"
        }
    }
}
