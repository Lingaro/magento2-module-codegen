{{ include(template_from_string(headerPHP)) }}
namespace {{ vendorName|pascal }}\{{ moduleName|pascal }}\Api;

interface {{ entityName|pascal }}ManagementInterface
{
    /**
{% for item in filters %}
{% if item.value is empty %}
     * @param {{ item.type }} ${{ item.field|camel }}
{% endif %}
{% endfor %}
     * @param int $pageSize
     * @param int $currentPage
{% if customEntityRepository is empty %}
     * @return \{{ vendorName|pascal }}\{{ moduleName|pascal }}\Api\Data\{{ entityName|pascal }}Interface[]
{% else %}
     * @return {{ customEntityType }}[]
{% endif %}
     */
    public function {{ functionName|camel }}(
{% for item in filters %}
{% if item.value is empty %}
        {{ item.type }} ${{ item.field|camel }},
{% endif %}
{% endfor %}
        int $pageSize = 0,
        int $currentPage = 1
    ): array;
}
