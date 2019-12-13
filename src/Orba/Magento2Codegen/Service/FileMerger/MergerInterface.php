<?php

namespace Orba\Magento2Codegen\Service\FileMerger;

interface MergerInterface
{
    public function merge(string $oldContent, string $newContent): string;
}