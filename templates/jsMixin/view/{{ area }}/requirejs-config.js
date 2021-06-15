/**
 * @copyright Copyright © {{ commentsYear }} {{ commentsCompanyName|raw }}. All rights reserved.
 * @author    {{ commentsUserEmail }}
 */

var config = {
    "config": {
        "mixins": {
            "{{ originalModule }}": {
                "{{ vendorName|pascal }}_{{ moduleName|pascal }}/js/{{ originalModule|kebab }}-mixin": true
            }
        }
    }
}
