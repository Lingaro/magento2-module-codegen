<?php

namespace Orba\Magento2Codegen\Service\FileMerger;

abstract class AbstractMerger implements MergerInterface
{
    /**
     * @var array
     */
    protected $params = [];

    /**
     * @var bool
     */
    protected $experimental = false;

    public function setParams(array $params): void
    {
        $this->params = $params;
    }

    public function isExperimental(): bool
    {
        return $this->experimental;
    }
}