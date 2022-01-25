{{ include(template_from_string(headerPHP)) }}
{% set class_namespace = commandFilePath|replace({'/': '\\'})|split('\\', -1)|join('\\')|raw %}
{% set class_name = commandFilePath|split('/')|last|raw %}
{% if class_namespace %}
namespace {{ vendorName|pascal }}\{{ moduleName|pascal }}\Console\Command\{{ class_namespace }};
{% else %}
namespace {{ vendorName|pascal }}\{{ moduleName|pascal }}\Console\Command;
{% endif %}

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class {{ class_name }}Command extends Command
{
    public function configure(): void
    {
        $this->setName('{{ commandName }}')
            ->setDescription('{{ commandDescription }}');
    }

    /**
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    protected function execute(InputInterface $input, OutputInterface $output): void
    {
        $output->writeln('Some actions here...');
    }
}
