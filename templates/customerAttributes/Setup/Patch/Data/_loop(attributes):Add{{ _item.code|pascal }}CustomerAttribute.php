{{ include(template_from_string(headerPHP)) }}
namespace {{ vendorName|pascal }}\{{ moduleName|pascal }}\Setup\Patch\Data;

use Magento\Customer\Model\Customer;
use Magento\Customer\Setup\CustomerSetup;
use Magento\Customer\Setup\CustomerSetupFactory;
use Magento\Eav\Model\Entity\Attribute\Set;
use Magento\Eav\Model\Entity\Attribute\SetFactory;
use Magento\Framework\Setup\ModuleDataSetupInterface;
use Magento\Framework\Setup\Patch\DataPatchInterface;
use Magento\Framework\Setup\Patch\PatchRevertableInterface;

class Add{{ _item.code|pascal }}CustomerAttribute implements DataPatchInterface, PatchRevertableInterface
{
    private ModuleDataSetupInterface $moduleDataSetup;
    private CustomerSetupFactory $customerSetupFactory;
    private SetFactory $attributeSetupFactory;

    public function __construct(
        ModuleDataSetupInterface $moduleDataSetup,
        CustomerSetupFactory $customerSetupFactory,
        SetFactory $attributeSetFactory
    ) {
        $this->moduleDataSetup = $moduleDataSetup;
        $this->customerSetupFactory = $customerSetupFactory;
        $this->attributeSetFactory = $attributeSetFactory;
    }

    public function apply(): void
    {
        /** @var CustomerSetup $customerSetup */
        $customerSetup = $this->customerSetupFactory->create(['setup' => $this->moduleDataSetup]);
        $customerEntity = $customerSetup->getEavConfig()->getEntityType(Customer::ENTITY);
        $attributeSetId = $customerEntity->getDefaultAttributeSetId();

        /** @var $attributeSet Set */
        $attributeSet = $this->attributeSetFactory->create();
        $attributeGroupId = $attributeSet->getDefaultGroupId($attributeSetId);

        $customerSetup->addAttribute(
            Customer::ENTITY,
            '{{ _item.code|snake }}',
            [
                'label' => '{{ _item.label }}',
                'input' => '{{ _item.input|snake }}',
{% if _item.is_used_in_grid == true and _item.type == 'text' %}
                'type' => 'varchar',
{% else %}
                'type' => '{{ _item.type|snake }}',
{% endif %}
{% if _item.source_model == 'existing' %}
                'source' => {{ _item.source_model_existing }}::class,
{% endif %}
{% if _item.source_model == 'custom' %}
                'source' => \{{ vendorName|pascal }}\{{ moduleName|pascal }}\Model\Customer\Attribute\Source\{{ _item.code|pascal }}Source::class,
{% endif %}
{% if _item.data_model %}
                'data' => {{ _item.data_model }}::class,
{% endif %}
{% if _item.note %}
                'note' => '{{ _item.note }}',
{% endif %}
                'required' => {{ _item.required ? '1' : '0' }},
                'system' => {{ _item.system ? '1' : '0' }},
{% if _item.default %}
                'default' => '{{ _item.default }}',
{% endif %}
                'unique' => {{ _item.unique ? '1' : '0' }},
{% if _item.validate_rules %}
                'validate_rules' => '{{ _item.validate_rules|raw}}',
{% endif %}
{% if _item.input_filter %}
                'input_filter' => '{{ _item.input_filter }}',
{% endif %}
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
{% if _item.is_visible_in_grid is not null %}
                'is_searchable_in_grid' => {{ _item.is_searchable_in_grid ? '1' : '0' }},
{% endif %}
                'position' => {{ _item.sort_order }},
                'visible' => {{ _item.visible ? '1' : '0' }},
                'user_defined' => {{ _item.user_defined ? '1' : '0' }},
{% if _item.frontend_model %}
                'frontend' => {{ _item.frontend_model }}::class,
{% endif %}
{% if _item.backend_model %}
                'backend' => {{ _item.backend_model }}::class,
{% endif %}
{% if _item.attribute_model %}
                'attribute_model' => {{ _item.attribute_model }}::class,
{% endif %}
{% if _item.table %}
                'table' => '{{ _item.table }}'
{% endif %}
            ]
        );

        $attribute = $customerSetup->getEavConfig()->getAttribute(Customer::ENTITY, '{{ _item.code|snake }}');
        $attribute->addData([
            'used_in_forms' => [
{% if _item.adminhtml_customer %}
                'adminhtml_customer',
{% endif %}
{% if _item.adminhtml_checkout %}
                'adminhtml_checkout',
{% endif %}
{% if _item.customer_account_create %}
                'customer_account_create',
{% endif %}
{% if _item.customer_account_edit %}
                'customer_account_edit'
{% endif %}
            ]
        ]);

        $attribute->addData([
            'attribute_set_id' => $attributeSetId,
            'attribute_group_id' => $attributeGroupId
        ]);

        $attribute->save();
    }

    public function revert(): void
    {
        /** @var CustomerSetup $customerSetup */
        $customerSetup = $this->customerSetupFactory->create(['setup' => $this->moduleDataSetup]);
        $customerSetup->removeAttribute(\Magento\Customer\Model\Customer::ENTITY, '{{ _item.code|snake }}');
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
