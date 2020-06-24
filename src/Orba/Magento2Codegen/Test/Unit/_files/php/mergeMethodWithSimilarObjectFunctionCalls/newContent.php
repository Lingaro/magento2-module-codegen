<?php

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
    private $poolObject2;

    /**
     * @return $this
     */
    public function existingSimilarFunction(): self
    {
        $pool = $this->source->getPool();
        $pool->addToPool('pool2', $this->poolObject2);
        return $this;
    }
}