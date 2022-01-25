{{ include(template_from_string(headerPHP)) }}
\Magento\Framework\Component\ComponentRegistrar::register(
    \Magento\Framework\Component\ComponentRegistrar::MODULE,
    '{{ vendorName|pascal }}_{{ moduleName|pascal }}',
    __DIR__
);
