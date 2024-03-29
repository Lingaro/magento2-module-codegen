<?php

/**
 * Copyright © 2023 Lingaro sp. z o.o. All rights reserved.
 * See LICENSE for license details.
 */

declare(strict_types=1);

namespace Lingaro\Magento2Codegen\Service;

use SplFileInfo;
use Symfony\Component\Finder\Finder;

use function str_replace;

class TasksExecutor
{
    public function execute(Finder $finder): void
    {
        $finder->name('*.php');
        foreach ($finder->files() as $file) {
            /** @var SplFileInfo $file */
            $className = str_replace(
                '/',
                '\\',
                str_replace(
                    BP . '/src',
                    '',
                    str_replace(
                        '.php',
                        '',
                        $file->getPathname()
                    )
                )
            );
            $obj = new $className();
            if ($obj instanceof TaskInterface) {
                $obj->execute();
            }
        }
    }
}
