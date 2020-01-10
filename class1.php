<?php

namespace C;

use Something\Fake\S1;
use Something\Fake\S5;
use Something\Fake\S4;
use Something\Fake\S7;

const SpaceSomething = 1;

/** bsd */
class class1 implements fake
{
    use Bizon;
    use Blozon;
    protected $total = 0;
    private $total1 = 0;

    protected $b;

    const ClassSomething1 = 1;
    const ClassSomething2 = 2;

    function __construct($a, $b)
    {
        $this->a = $a;
        $this->b = $b;
    }

    public function common($a, $b)
    {

    }

    function subtract(int $c, int $a): int
    {
        $this->total -= $a;
    }

    private function add($g): int
    {

    }
}

