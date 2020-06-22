<?php

namespace Namespace1;

use Namespace1\Extends1;
use Namespace2\Interface2;
use Namespace1\ImportClass2;

class Class1 extends Extends1 implements Interface2
{
    /**
     * @var Namespace1\ImportClass2
     */
    private $importClass2;

    private $someParam1;

    private $someParam2;

    // you should add something here

    public function __construct(ImportClass2 $importClass2, array $map = [])
    {
        $this->importClass2 = $importClass2;
        $this->someParam1 = new SomeParam($map);
        $this->someParam2 = new SomeParam($map);
    }

    public function getImportClass2(): ImportClass2
    {
        return $this->importClass2;
    }

    public function doSomethingWithClass2(): void
    {
        $class = $this->getImportClass2();
        $class->execute();
    }
}