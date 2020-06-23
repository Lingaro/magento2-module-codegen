var config = {
    map: {
        '*': {
            {{ jsModuleName|snake }}: '{{ vendorName|pascal }}_{{ moduleName|pascal }}/js/{{ jsModuleName|snake }}'
        }
    }
}
