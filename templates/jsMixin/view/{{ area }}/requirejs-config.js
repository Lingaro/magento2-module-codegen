var config = {
    config: {
        mixins: {
            "{{ originalModule }}": {
                "{{ vendorName|pascal }}_{{ moduleName|pascal }}/js/{{ originalModule|kebab }}-mixin": true
            }
        }
    }
}
