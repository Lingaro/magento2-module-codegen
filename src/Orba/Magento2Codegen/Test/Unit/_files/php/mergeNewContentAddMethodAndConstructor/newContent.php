<?php

namespace Namespace1;

use Namespace1\Tester\Php as PhpTester;

class Class1
{
    /**
     * @var FriesFryer
     */
    private $friesFryer;

    public function __construct(FriesFryer $friesFryer)
    {
        $this->friesFryer = $friesFryer;
    }

    public function getFriesFryer(): FriesFryer
    {
        return $this->friesFryer;
    }
}