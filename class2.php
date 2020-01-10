<?php

namespace C;

use Something\Fake\S4 as Franca;

/** asd */
class class1 implements fake
{
    protected $runs = 0;
    use Blozon;
    private $total = 0;
    private $total2, $total1 = 0;
    private $materac = [];

    protected $a;

    private $b;

    const ClassSomething2 = 'c';
    const ClassSomething3 = 'a';

    public function __construct(int $a, int $c, string $b = null)
    {
        $this->b = $b;
    }

    public function common($b, $c, $d = null): int
    {

    }

    private function subtract(int $a): int
    {
        $this->total -= $a;
    }

    public function add(int $a, $g): int
    {
        return $this->total += $a;
    }

    public function doesNotExistInClass1(int $b = 1)
    {
        return $b = 99;
    }
}