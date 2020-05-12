<?php
/**
 * {{ commandName|pascal }}Command
 *
 * @copyright Copyright © {{ commentsYear }} {{ commentsCompanyName}}. All rights reserved.
 * @author    {{ commentsUserEmail }}
 */

namespace {{ vendorName|pascal }}\{{ moduleName|pascal }}\Console\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class {{ commandName|pascal }}Command extends Command
{
    public function configure()
    {
        $this->setName('{{ commandName|kebab }}')
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