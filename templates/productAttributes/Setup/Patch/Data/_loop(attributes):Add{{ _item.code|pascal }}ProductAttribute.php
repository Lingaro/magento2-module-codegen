<?php

/**
 * @copyright Copyright © {{ commentsYear }} {{ commentsCompanyName}}. All rights reserved.
 * @author    {{ commentsUserEmail }}
 */

declare(strict_types=1);

namespace {{ vendorName|pascal }}\{{ moduleName|pascal }}\Setup\Patch\Data;

use Magento\Catalog\Model\Product;
use Magento\Eav\Setup\EavSetup;
use Magento\Eav\Setup\EavSetupFactory;
use Magento\Framework\Setup\ModuleDataSetupInterface;
use Magento\Framework\Setup\Patch\DataPatchInterface;
use Magento\Framework\Setup\Patch\PatchRevertableInterface;

class Add{{ _item.code|pascal }}ProductAttribute implements DataPatchInterface, PatchRevertableInterface
{
    private ModuleDataSetupInterface $moduleDataSetup;
    private EavSetupFactory $eavSetupFactory;

    public function __construct(
       ModuleDataSetupInterface $moduleDataSetup,
       EavSetupFactory $eavSetupFactory
    ) {
       $this->moduleDataSetup = $moduleDataSetup;
       $this->eavSetupFactory = $eavSetupFactory;
    }

    public function apply(): void
    {
        /** @var EavSetup $eavSetup */

        $eavSetup = $this->eavSetupFactory->create(['setup' => $this->moduleDataSetup]);

        $eavSetup->addAttribute(
            Product::ENTITY,
            '{{ _item.code|snake }}',
            [
                'label' => '{{ _item.label }}',
                'type' => '{{ _item.type }}',
                'input' => '{{ _item.input }}',
{% if _item.note %}
                'note' => '{{ _item.note }}',
{% endif %}
                'required' => {{ _item.required ? '1' : '0' }},
                'global' => {{ _item.scope == 'global' ? '1' : (_item.scope == 'website' ? '2' : '0') }},
{% if _item.default %}
                'default' => '{{ _item.default }}',
{% endif %}
                'unique' => {{ _item.unique ? '1' : '0' }},
{% if _item.frontend_class %}
                'frontend_class' => '{{ _item.frontend_class }}',
{% endif %}
                'is_used_in_grid' => {{ _item.is_used_in_grid ? '1' : '0' }},
{% if _item.is_filterable_in_grid is not null %}
                'is_filterable_in_grid' => {{ _item.is_filterable_in_grid ? '1' : '0' }},
{% endif %}
{% if _item.is_visible_in_grid is not null %}
                'is_visible_in_grid' => {{ _item.is_visible_in_grid ? '1' : '0' }},
{% endif %}
                'searchable' => {{ _item.searchable ? '1' : '0' }},
{% if _item.search_weight is not null %}
                'search_weight' => {{ _item.search_weight }},
{% endif %}
{% if _item.visible_in_advanced_search is not null %}
                'visible_in_advanced_search' => {{ _item.visible_in_advanced_search ? '1' : '0' }},
{% endif %}
                'comparable' => {{ _item.comparable ? '1' : '0' }},
                'filterable' => {{ _item.filterable == 'no' ? '0' : (_item.filterable == 'filterable (with results)' ? '1' : '2') }},
                'filterable_in_search' => {{ _item.filterable_in_search ? '1' : '0' }},
                'position' => {{ _item.position }},
                'used_for_promo_rules' => {{ _item.used_for_promo_rules ? '1' : '0' }},
                'visible_on_front' => {{ _item.visible_on_front ? '1' : '0' }},
                'is_html_allowed_on_front' => {{ _item.is_html_allowed_on_front ? '1' : '0' }},
                'used_in_product_listing' => {{ _item.used_in_product_listing ? '1' : '0' }},
                'used_for_sort_by' => {{ _item.used_for_sort_by ? '1' : '0' }},
                'visible' => {{ _item.visible ? '1' : '0' }},
{% if _item.wysiwyg_enabled is not null %}
                'wysiwyg_enabled' => {{ _item.wysiwyg_enabled ? '1' : '0' }},
{% endif %}
                'user_defined' => {{ _item.user_defined ? '1' : '0' }},
{% if _item.frontend_model %}
                'frontend' => {{ _item.frontend_model }}::class,
{% endif %}
{% if _item.input_renderer %}
                'input_renderer' => {{ _item.input_renderer }}::class,
{% endif %}
{% if _item.backend_model %}
                'backend' => {{ _item.backend_model }}::class,
{% endif %}
{% if _item.source_model == 'existing' %}
                'source' => {{ _item.source_model_existing }}::class,
{% endif %}
{% if _item.source_model == 'custom' %}
                'source' => \{{ vendorName|pascal }}\{{ moduleName|pascal }}\Model\Product\Attribute\Source\{{ _item.code|pascal }}Source::class,
{% endif %}
{% if _item.source_model == 'skip' %}
                'source' => '',
{% endif %}
{% if _item.attribute_model %}
                'attribute_model' => {{ _item.attribute_model }}::class,
{% endif %}
{% if _item.table %}
                'table' => '{{ _item.table }}',
{% endif %}
                'attribute_set' => '{{ _item.attribute_set }}',
                'group' => '{{ _item.group }}',
{% set apply_to = '' %}
{% if _item.apply_to_simple %}
{% set apply_to = apply_to ~ 'simple,' %}
{% endif %}
{% if _item.apply_to_configurable %}
{% set apply_to = apply_to ~ 'configurable,' %}
{% endif %}
{% if _item.apply_to_grouped %}
{% set apply_to = apply_to ~ 'grouped,' %}
{% endif %}
{% if _item.apply_to_virtual %}
{% set apply_to = apply_to ~ 'virtual,' %}
{% endif %}
{% if _item.apply_to_downloadable %}
{% set apply_to = apply_to ~ 'downloadable,' %}
{% endif %}
{% if _item.apply_to_bundle %}
{% set apply_to = apply_to ~ 'bundle,' %}
{% endif %}
{% if _item.apply_to_giftcard %}
{% set apply_to = apply_to ~ 'giftcard,' %}
{% endif %}
                'apply_to' => '{{ apply_to|trim(',') }}',
{% if _item.options %}
                'option' => ['values' => [{{ _item.options|split(',')|map(value => "'#{value}'")|join(', ')|raw }}]],
{% endif %}
            ]
        );
    }

    public function revert(): void
    {
        /** @var EavSetup $eavSetup */
        $eavSetup = $this->eavSetupFactory->create(['setup' => $this->moduleDataSetup]);
        $eavSetup->removeAttribute(Product::ENTITY, '{{ _item.code|snake }}');
    }

    public function getAliases(): array
    {
        return [];
    }

    public static function getDependencies(): array
    {
{% if patchDependencies is not empty %}
        return [
{% for item in patchDependencies %}
            {{ item.name }}::class,
{% endfor %}
        ];
{% else %}
        return [];
{% endif %}
   }
}
