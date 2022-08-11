{{ include(template_from_string(headerPHP)) }}
namespace {{ vendorName|pascal }}\{{ moduleName|pascal }}\Setup\Patch\Data;

use Magento\Eav\Setup\EavSetup;
use Magento\Eav\Setup\EavSetupFactory;
use Magento\Framework\Setup\ModuleDataSetupInterface;
use Magento\Framework\Setup\Patch\DataPatchInterface;
use Magento\Framework\Setup\Patch\PatchRevertableInterface;

class Add{{ _item.code|pascal }}CategoryAttribute implements DataPatchInterface, PatchRevertableInterface
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
            \Magento\Catalog\Model\Category::ENTITY,
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
{% if ( _item.formElement == 'multiselect' ) and ( _item.backend_model == '' ) %}
                'backend' => \Magento\Eav\Model\Entity\Attribute\Backend\ArrayBackend::class,
{% endif %}
{% if ( _item.formElement == 'imageUploader' ) and ( _item.backend_model == '' ) %}
                'backend' => \Magento\Catalog\Model\Category\Attribute\Backend\Image::class,
{% endif %}
{% if _item.backend_model %}
                'backend' => {{ _item.backend_model }}::class,
{% endif %}
{% if _item.source_model == 'existing' and _item.source_model_existing is not empty %}
                'source' => {{ _item.source_model_existing }}::class,
{% endif %}
{% if _item.source_model == 'custom' %}
                'source' => \{{ vendorName|pascal }}\{{ moduleName|pascal }}\Model\Category\Attribute\Source\{{ _item.code|pascal }}Source::class,
{% endif %}
{% if _item.attribute_model %}
                'attribute_model' => {{ _item.attribute_model }}::class,
{% endif %}
{% if _item.table %}
                'table' => '{{ _item.table }}',
{% endif %}
{% if fieldset == 'existing' %}
                'group' => '{{ fieldset_existing }}',
{% endif %}
{% if fieldset == 'custom' %}
                'group' => '{{ fieldset_custom }}',
{% endif %}
            ]
        );
    }

    public function revert(): void
    {
        /** @var EavSetup $eavSetup */
        $eavSetup = $this->eavSetupFactory->create(['setup' => $this->moduleDataSetup]);
        $eavSetup->removeAttribute(\Magento\Catalog\Model\Category::ENTITY, '{{ _item.code|snake }}');
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
{% if item.name is not empty %}
            {{ item.name }}::class,
{% endif %}
{% endfor %}
        ];
{% else %}
        return [];
{% endif %}
   }
}
