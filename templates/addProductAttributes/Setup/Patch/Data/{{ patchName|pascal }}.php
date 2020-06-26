<?php
/**
 * @copyright Copyright © {{ commentsYear }} {{ commentsCompanyName}}. All rights reserved.
 * @author    {{ commentsUserEmail }}
 */

namespace {{ vendorName|pascal }}\{{ moduleName|pascal }}\Setup\Patch\Data;

use Magento\Catalog\Model\Product;
use Magento\Eav\Setup\EavSetup;
use Magento\Eav\Setup\EavSetupFactory;
use Magento\Framework\Setup\ModuleDataSetupInterface;
use Magento\Framework\Setup\Patch\DataPatchInterface;

class {{ patchName|pascal }} implements DataPatchInterface
{
    /**
     * @var ModuleDataSetupInterface
     */
    private $moduleDataSetup;

    /**
     * @var EavSetupFactory
     */
    private $eavSetupFactory;

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

        foreach ($this->getAttributes() as $attributeCode => $attributeData) {
            $eavSetup->addAttribute(Product::ENTITY, $attributeCode, $attributeData);
        }
    }

    private function getAttributes(): array
    {
        return [
{% for item in attributes %}
            '{{ item.code|snake }}' => [
                'label' => '{{ item.label }}',
                'type' => '{{ item.type }}',
                'input' => '{{ item.input }}',
{% if item.note %}
                'note' => '{{ item.note }}',
{% endif %}
                'required' => {{ item.required ? '1' : '0' }},
                'global' => {{ item.scope == 'global' ? '1' : (item.scope == 'website' ? '2' : '0') }},
{% if item.default %}
                'default' => '{{ item.default }}',
{% endif %}
                'unique' => {{ item.unique ? '1' : '0' }},
{% if item.frontend_class %}
                'frontend_class' => '{{ item.frontend_class }}',
{% endif %}
                'is_used_in_grid' => {{ item.is_used_in_grid ? '1' : '0' }},
{% if item.is_filterable_in_grid is not null %}
                'is_filterable_in_grid' => {{ item.is_filterable_in_grid ? '1' : '0' }},
{% endif %}
{% if item.is_visible_in_grid is not null %}
                'is_visible_in_grid' => {{ item.is_visible_in_grid ? '1' : '0' }},
{% endif %}
                'searchable' => {{ item.searchable ? '1' : '0' }},
{% if item.search_weight is not null %}
                'search_weight' => {{ item.search_weight }},
{% endif %}
{% if item.visible_in_advanced_search is not null %}
                'visible_in_advanced_search' => {{ item.visible_in_advanced_search ? '1' : '0' }},
{% endif %}
                'comparable' => {{ item.comparable ? '1' : '0' }},
                'filterable' => {{ item.filterable == 'no' ? '0' : (item.filterable == 'filterable (with results)' ? '1' : '2') }},
                'filterable_in_search' => {{ item.filterable_in_search ? '1' : '0' }},
                'position' => {{ item.position }},
                'used_for_promo_rules' => {{ item.used_for_promo_rules ? '1' : '0' }},
                'visible_on_front' => {{ item.visible_on_front ? '1' : '0' }},
                'is_html_allowed_on_front' => {{ item.is_html_allowed_on_front ? '1' : '0' }},
                'used_in_product_listing' => {{ item.used_in_product_listing ? '1' : '0' }},
                'used_for_sort_by' => {{ item.used_for_sort_by ? '1' : '0' }},
                'visible' => {{ item.visible ? '1' : '0' }},
{% if item.wysiwyg_enabled is not null %}
                'wysiwyg_enabled' => {{ item.wysiwyg_enabled ? '1' : '0' }},
{% endif %}
                'user_defined' => {{ item.user_defined ? '1' : '0' }},
{% if item.frontend_model %}
                'frontend' => {{ item.frontend_model }}::class,
{% endif %}
{% if item.input_renderer %}
                'input_renderer' => {{ item.input_renderer }}::class,
{% endif %}
{% if item.backend_model %}
                'backend' => {{ item.backend_model }}::class,
{% endif %}
{% if item.source_model %}
                'source' => {{ item.source_model }}::class,
{% endif %}
{% if item.attribute_model %}
                'attribute_model' => {{ item.attribute_model }}::class,
{% endif %}
{% if item.table %}
                'table' => '{{ item.table }}',
{% endif %}
                'attribute_set' => '{{ item.attribute_set }}',
                'group' => '{{ item.group }}',
{% set apply_to = '' %}
{% if item.apply_to_simple %}
{% set apply_to = apply_to ~ 'simple,' %}
{% endif %}
{% if item.apply_to_configurable %}
{% set apply_to = apply_to ~ 'configurable,' %}
{% endif %}
{% if item.apply_to_grouped %}
{% set apply_to = apply_to ~ 'grouped,' %}
{% endif %}
{% if item.apply_to_virtual %}
{% set apply_to = apply_to ~ 'virtual,' %}
{% endif %}
{% if item.apply_to_downloadable %}
{% set apply_to = apply_to ~ 'downloadable,' %}
{% endif %}
{% if item.apply_to_bundle %}
{% set apply_to = apply_to ~ 'bundle,' %}
{% endif %}
{% if item.apply_to_giftcard %}
{% set apply_to = apply_to ~ 'giftcard,' %}
{% endif %}
                'apply_to' => '{{ apply_to|trim(',') }}',
{% if item.options %}
                'option' => ['values' => [{{ item.options|split(',')|map(value => "'#{value}'")|join(', ')|raw }}]],
{% endif %}
            ],
{% endfor %}
        ];
    }

    /**
     * {@inheritdoc}
     */
    public static function getDependencies(): array
    {
        return [
{% if hasPatchDependencies %}
{% for item in patchDependencies %}
            {{ item.name }}::class,
{% endfor %}
{% endif %}
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function getAliases(): array
    {
        return [];
    }
}
