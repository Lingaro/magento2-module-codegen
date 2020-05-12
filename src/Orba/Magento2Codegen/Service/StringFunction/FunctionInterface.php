<?php

namespace Orba\Magento2Codegen\Service\StringFunction;

interface FunctionInterface
{
    public function execute(...$args):? string;
}