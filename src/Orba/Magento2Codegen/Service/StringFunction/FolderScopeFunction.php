<?php

namespace Orba\Magento2Codegen\Service\StringFunction;

class FolderScopeFunction implements FunctionInterface
{
    public function execute(...$args): ?string
    {
        return $this->folderScope(...$args);
    }

    private function folderScope(string $scope): string
    {
        if (in_array($scope, ['frontend', 'adminhtml'])) {
            return '/' . $scope;
        }
        return '';
    }
}
