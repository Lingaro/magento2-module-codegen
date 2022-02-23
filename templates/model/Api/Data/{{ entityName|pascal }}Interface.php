{{ include(template_from_string(headerPHP)) }}
namespace {{ vendorName|pascal }}\{{ moduleName|pascal }}\Api\Data;

interface {{ entityName|pascal }}Interface
{
    /**
     * @return int|null
     */
    public function getId();

    /**
     * @param int $value
     * @return void
     */
    public function setId($value);
{% for item in fields %}

    /**
     * @return {{ databaseTypeToPHP(item.databaseType) }}|null
     */
    public function get{{ item.name|pascal }}(): ?{{ databaseTypeToPHP(item.databaseType) }};

    /**
     * @param {{ databaseTypeToPHP(item.databaseType) }} $value
     * @return void
     */
    public function set{{ item.name|pascal }}({{ databaseTypeToPHP(item.databaseType) }} $value): void;
{% endfor %}
}
