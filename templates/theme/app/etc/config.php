<?php
return [
{% if assignTo == 'default' %}
    'system' => [
        'default' => [
            'design' => [
                'theme' => [
                    'theme_id' => 'frontend/{{ vendorName|pascal }}/{{ themeName|snake }}'
                ]
            ]
        ]
    ],
{% endif %}
{% if assignTo == 'website' %}
    'system' => [
        'websites' => [
            '{{ websiteCode|raw }}' => [
                'design' => [
                    'theme' => [
                        'theme_id' => 'frontend/{{ vendorName|pascal }}/{{ themeName|snake }}'
                    ]
                ]
            ]
        ]
    ],
{% endif %}
{% if assignTo == 'store' %}
    'system' => [
        'stores' => [
            '{{ storeCode|raw }}' => [
                'design' => [
                    'theme' => [
                        'theme_id' => 'frontend/{{ vendorName|pascal }}/{{ themeName|snake }}'
                    ]
                ]
            ]
        ]
    ],
{% endif %}
    'themes' => [
        'frontend/{{ vendorName|pascal }}/{{ themeName|snake }}' => [
{% if parent == 'other' %}
            'parent_id' => '{{ parentName|raw }}',
{% else %}
            'parent_id' => '{{ parent|raw }}',
{% endif %}
            'theme_path' => '{{ vendorName|pascal }}/{{ themeName|snake }}',
            'theme_title' => '{{ vendorName|pascal }} {{ themeName|snake }}',
            'is_featured' => '0',
            'area' => 'frontend',
            'type' => '0',
            'code' => '{{ vendorName|pascal }}/{{ themeName|snake }}'
        ]
    ]
];
