<?php

namespace Orba\Magento2Codegen\Service\Input;

use InvalidArgumentException;
use Orba\Magento2Codegen\Command\Template\GenerateCommand;
use Symfony\Component\Console\Input\InputInterface;

class RootDirValidator implements ValidatorInterface
{
    public function validate(InputInterface $input): bool
    {
        $rootDir = $input->getOption(GenerateCommand::OPTION_ROOT_DIR);
        if (strpos($rootDir, BP) !== false) {
            throw new InvalidArgumentException('Code cannot be generated inside application dir. Please run command from destination dir or specify proper dir using --root-dir option.');
        }
        return true;
    }
}