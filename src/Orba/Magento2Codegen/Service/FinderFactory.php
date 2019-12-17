<?php

namespace Orba\Magento2Codegen\Service;

use Symfony\Component\Finder\Finder;

class FinderFactory
{
    public function create(): Finder
    {
        return new Finder();
    }
}