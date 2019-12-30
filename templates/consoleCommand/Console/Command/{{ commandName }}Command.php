<?php
/**
 * {{ commandName }}Command
 *
 * @copyright Copyright © {{ commentsYear }} {{ commentsCompanyName}}. All rights reserved.
 * @author    {{ commentsUserEmail }}
 */

namespace {{ vendorName }}\{{ moduleName }}\Console\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class {{ commandName}}Command extends Command
{
    public function configure()
    {
        $this->setName('{{ commandName|lower }}')
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