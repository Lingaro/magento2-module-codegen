{{ include(template_from_string(headerPHP)) }}
namespace {{ vendorName|pascal }}\{{ moduleName|pascal }}\Cron;

class {{ jobName|pascal }}
{
    public function execute(): void
    {

    }
}
