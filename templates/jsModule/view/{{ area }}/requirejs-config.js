var config = {
    "map": {
        "*": {
            "{{ name|camel }}": "{{ vendorName|pascal }}_{{ moduleName|pascal }}/js/{{ name|snake }}"
        }
    }
}
