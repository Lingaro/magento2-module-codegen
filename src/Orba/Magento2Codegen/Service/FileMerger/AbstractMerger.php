<?php

namespace Orba\Magento2Codegen\Service\FileMerger;

abstract class AbstractMerger implements MergerInterface
{
    /**
     * @var array
     */
    protected $params = [];

    public function setParams(array $params): void
    {
        $this->params = $params;
    }
}