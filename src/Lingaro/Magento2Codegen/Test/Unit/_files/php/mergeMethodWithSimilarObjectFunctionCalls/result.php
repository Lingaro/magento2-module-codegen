<?php

/**
 * Copyright © 2023 Lingaro sp. z o.o. All rights reserved.
 * See LICENSE for license details.
 */
namespace Namespace1;

class Class1
{
    /**
     * @var Source
     */
    private $source;
    /**
     * @var Object
     */
    private $poolObject1;
    /**
     * @var Object
     */
    private $poolObject2;
    /**
     * @return $this
     */
    public function existingSimilarFunction() : self
    {
        $pool = $this->source->getPool();
        $pool->addToPool('pool1', $this->poolObject1);
        $pool->addToPool('pool2', $this->poolObject2);
        return $this;
    }
}