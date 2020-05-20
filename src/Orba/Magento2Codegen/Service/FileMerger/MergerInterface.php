<?php

namespace Orba\Magento2Codegen\Service\FileMerger;

interface MergerInterface
{
    public function setParams(array $params): void;

    public function merge(string $oldContent, string $newContent): string;

    public function isExperimental(): bool;
}