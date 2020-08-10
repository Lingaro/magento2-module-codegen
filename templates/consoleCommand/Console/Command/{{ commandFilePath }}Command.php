<?php
/**
 * {{ commandName|pascal }}Command
 *
 * @copyright Copyright © {{ commentsYear }} {{ commentsCompanyName}}. All rights reserved.
 * @author    {{ commentsUserEmail }}
 */

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
    public function __construct(string $name = null)
    {
        parent::__construct($name);
    }

    public function configure()
    {
        $this->setName('{{ commandName }}')
            ->setDescription('{{ commandDescription }}');
    }

    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $output->writeln('Some actions here...');
    }
}
