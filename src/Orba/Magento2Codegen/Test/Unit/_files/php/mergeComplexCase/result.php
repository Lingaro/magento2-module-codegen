<?php

/**
 * Copyright © 2023 Lingaro sp. z o.o. All rights reserved.
 * See LICENSE for license details.
 */

declare (strict_types=1);

namespace Namespace1;

use Namespace1\Extends1;
use Namespace2\Interface1;
use Namespace1\ImportClass1;
use Namespace2\Interface2;
use Namespace1\ImportClass2;

class Class1 extends Extends1 implements Interface1, Interface2
{
    /**
     * @var Namespace1\ImportClass1
     */
    private $importClass1;
    private $someParam1;
    /**
     * @var Namespace1\ImportClass2
     */
    private $importClass2;
    private $someParam2;
    // you should add something here
    public function __construct(ImportClass1 $importClass1, array $map = [], ImportClass2 $importClass2)
    {
        $this->importClass1 = $importClass1;
        $this->someParam1 = new SomeParam($map);
        $this->importClass2 = $importClass2;
        $this->someParam2 = new SomeParam($map);
    }
    public function existingDataPoolFunction() : self
    {
        $dataPool = $this->getDataPool();
        $dataPool->setData('param1', $this->someParam1);
        $dataPool->setData('param2', $this->someParam2);
        return $this;
    }
    public function getImportClass1() : ImportClass1
    {
        return $this->importClass1;
    }
    public function doSomethingWithClass1() : void
    {
        $class = $this->getImportClass1();
        $class->execute();
    }
    public function getImportClass2() : ImportClass2
    {
        return $this->importClass2;
    }
    public function doSomethingWithClass2() : void
    {
        $class = $this->getImportClass2();
        $class->execute();
    }
}
