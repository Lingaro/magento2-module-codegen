<?php
declare(strict_types=1);

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
        $attributes = [
        {% for item in attributes %}
    '{{ item.code|snake }}' => [
{% if item.apply_to is not null %}
                'apply_to' => '{{ item.apply_to }}',
{% endif %}
{% if item.label is not null %}
                'label' => '{{ item.label }}',
{% endif %}
{% if item.attribute_model is not null %}
                'attribute_model' => '{{ item.attribute_model }}',
{% endif %}
{% if item.attribute_set is not null %}
                'attribute_set' => '{{ item.attribute_set }}',
{% endif %}
{% if item.backend is not null %}
                'backend' => '{{ item.backend }}',
{% endif %}
{% if item.default is not null %}
                'default' => '{{ item.default }}',
{% endif %}
{% if item.frontend_class is not null %}
                'frontend_class' => '{{ item.frontend_class }}',
{% endif %}
{% if item.frontend is not null %}
                'frontend' => '{{ item.frontend }}',
{% endif %}
                'global' => '{{ item.global }}',
{% if item.group is not null %}
                'group' => '{{ item.group }}',
{% endif %}
{% if item.input_rendered is not null %}
                'input_rendered' => '{{ item.input_rendered }}',
{% endif %}
{% if item.note is not null %}
                'note' => '{{ item.note }}',
{% endif %}
{% if item.option is not null %}
                'option' => '{{ item.option }}',
{% endif %}
{% if item.sort_order is not null %}
                'sort_order' => '{{ item.sort_order }}',
{% endif %}
{% if item.source is not null %}
                'source' => '{{ item.source }}',
{% endif %}
{% if item.system is not null %}
                'system' => '{{ item.system }}',
{% endif %}
{% if item.table is not null %}
                'table' => '{{ item.table }}',
{% endif %}
                'comparable' => '{{ item.comparable }}',
                'input' => '{{ item.input }}',
                'is_filterable_in_grid' => '{{ item.is_filterable_in_grid }}',
                'is_html_allowed_on_front' => '{{ item.is_html_allowed_on_front }}',
                'is_used_in_grid' => '{{ item.is_used_in_grid }}',
                'is_visible_in_grid' => '{{ item.is_visible_in_grid }}',
                'type' => '{{ item.type }}',
                'position' => '{{ item.position }}',
                'filterable_in_search' => '{{ item.filterable_in_search }}',
                'filterable' => '{{ item.filterable }}',
                'required' => '{{ item.required }}',
                'searchable' => '{{ item.searchable }}',
                'unique' => '{{ item.unique }}',
                'used_for_promo_rules' => '{{ item.used_for_promo_rules }}',
                'used_for_sort_by' => '{{ item.used_for_sort_by }}',
                'used_in_product_listing' => '{{ item.used_in_product_listing }}',
                'user_defined' => '{{ item.user_defined }}',
                'visible_in_advanced_search' => '{{ item.visible_in_advanced_search }}',
                'visible_on_front' => '{{ item.visible_on_front }}',
                'visible' => '{{ item.visible }}',
                'wysiwyg_enabled' => '{{ item.wysiwyg_enabled }}',
            ],
{% endfor %}
        ];
        $attributes = array_filter($attributes, [$this, 'removeEmptyWithoutZeroFilter']);

        return $attributes;
    }

    /**
     * @param $value
     * @return bool
     */
    private function removeEmptyWithoutZeroFilter($value): bool
    {
        return !empty($value) || $value === 0;
    }

    /**
     * {@inheritdoc}
     */
    public static function getDependencies(): array
    {
        return [
{% for item in dependencies %}
{%if item.name is not null%}{{ item.name }}::class, {% endif %}
{% endfor %}
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
