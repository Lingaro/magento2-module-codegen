<?php

namespace Namespace1;

use Namespace1\Tester\Php as PhpTester;
class Class1
{
    /**
     * @var PhpTester
     */
    private $phpTester;
    /**
     * @var FriesFryer
     */
    private $friesFryer;
    public function __construct(PhpTester $phpTester, FriesFryer $friesFryer)
    {
        $this->phpTester = $phpTester;
        $this->friesFryer = $friesFryer;
    }
    public function getPhpTester() : PhpTester
    {
        return $this->phpTester;
    }
    public function getFriesFryer() : FriesFryer
    {
        return $this->friesFryer;
    }
}