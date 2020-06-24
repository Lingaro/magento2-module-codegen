<?php

namespace Namespace1;

use Namespace1\Extends1;
use Namespace2\Interface1;
use Namespace1\ImportClass1;

class Class1 extends Extends1 implements Interface1
{
    /**
     * @var Namespace1\ImportClass1
     */
    private $importClass1;

    private $someParam1;

    // you should add something here

    public function __construct(ImportClass1 $importClass1, array $map = [])
    {
        $this->importClass1 = $importClass1;
        $this->someParam1 = new SomeParam($map);
    }

    public function existingDataPoolFunction()
    {
        $dataPool = $this->getDataPool();
        $dataPool->setData('param1', $this->someParam1);
        return $this;
    }

    public function getImportClass1(): ImportClass1
    {
        return $this->importClass1;
    }

    public function doSomethingWithClass1(): void
    {
        $class = $this->getImportClass1();
        $class->execute();
    }
}