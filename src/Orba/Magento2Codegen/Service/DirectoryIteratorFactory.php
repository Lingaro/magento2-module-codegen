<?php

namespace Orba\Magento2Codegen\Service;

use \RecursiveDirectoryIterator;

class DirectoryIteratorFactory
{
    public function create(string $dir): RecursiveDirectoryIterator
    {
        $directoryIterator = new RecursiveDirectoryIterator($dir);
        $directoryIterator->setFlags(RecursiveDirectoryIterator::SKIP_DOTS);
        return $directoryIterator;
    }
}