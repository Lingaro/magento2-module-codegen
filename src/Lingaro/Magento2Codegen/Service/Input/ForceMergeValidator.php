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

class ForceMergeValidator implements ValidatorInterface
{
    public function validate(InputInterface $input): bool
    {
        $forceMerge = $input->getOption(GenerateCommand::OPTION_FORCE_MERGE);
        if ($forceMerge !== null) {
            if (
                $forceMerge === GenerateCommand::FORCE_MERGE_ALL ||
                $forceMerge === GenerateCommand::FORCE_MERGE_NONEXPERIMENTAL
            ) {
                return true;
            }
            throw new InvalidArgumentException(
                'Invalid value provided for --force-merge option. Valid values are: all or nonexperimental.'
            );
        }
        return true;
    }
}
