/**
 * @copyright Copyright © {{ commentsYear }} {{ commentsCompanyName|raw }}. All rights reserved.
 * @author    {{ commentsUserEmail }}
 */

var config = {
    "map": {
        "*": {
            "{{ name|camel }}": "{{ vendorName|pascal }}_{{ moduleName|pascal }}/js/{{ name|snake }}"
        }
    }
}
