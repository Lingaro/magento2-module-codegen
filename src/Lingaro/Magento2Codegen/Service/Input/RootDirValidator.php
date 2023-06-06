<?php

/**
 * Copyright © 2023 Lingaro sp. z o.o. All rights reserved.
 * See LICENSE for license details.
 */

declare(strict_types=1);

namespace Lingaro\Magento2Codegen\Service\Input;

use InvalidArgumentException;
use Lingaro\Magento2Codegen\Command\Template\GenerateCommand;
use Symfony\Component\Console\Input\InputInterface;

use function strpos;

class RootDirValidator implements ValidatorInterface
{
    public function validate(InputInterface $input): bool
    {
        $rootDir = $input->getOption(GenerateCommand::OPTION_ROOT_DIR);
        if (strpos($rootDir, BP) !== false) {
            throw new InvalidArgumentException(
                'Code cannot be generated inside application dir. '
                . 'Please run command from destination dir or specify proper dir using --root-dir option.'
            );
        }
        return true;
    }
}
