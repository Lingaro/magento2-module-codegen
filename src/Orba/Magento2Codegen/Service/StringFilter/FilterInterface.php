<?php

namespace Orba\Magento2Codegen\Service\StringFilter;

interface FilterInterface
{
    public function filter(string $text): string;
}