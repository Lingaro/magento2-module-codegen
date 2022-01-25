{{ include(template_from_string(headerPHP)) }}
use \Magento\Framework\Component\ComponentRegistrar;

ComponentRegistrar::register(
    ComponentRegistrar::THEME,
    'frontend/{{ vendorName|pascal }}/{{ themeName|snake }}',
    __DIR__
);
