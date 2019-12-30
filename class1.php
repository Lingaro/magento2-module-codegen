<?php

use Something\Fake\S1, Something\Fake\S3;
use Something\Fake\S4;
use Something\Fake\S7;
use Something\Fake\S5, Something\Fake\S6;

const SpaceSomething = 1;

class class1 implements fake
{
    use Bizon;
    protected $total = 0;
    protected $total1 = 0;

    protected $b;

    const ClassSomething1 = 1;
    const ClassSomething2 = 2;

    function __construct(string $a = null)
    {
        $this->a = $a;
    }

    public function common($a, $b)
    {

    }

    function subtract(int $c, int $a): int
    {
        $this->total -= $a;
    }
}

