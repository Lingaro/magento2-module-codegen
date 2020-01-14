<?php

namespace Orba\Magento2Codegen\Service;

use SplFileInfo;
use Symfony\Component\Finder\Finder;

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
                /** @var TaskInterface $obj */
                $obj->execute();
            }
        }
    }
}