<?php



use Something\Fake\S1, Something\Fake\S2;
use Something\Fake\S3;
use Something\Fake\S4 as Franca;

/** asd */
class class1 implements fake
{
    protected $runs = 0;
    use Blozon;

    protected $a;

    private $b;

    const ClassSomething2 = 'c';
    const ClassSomething3 = 'a';

    public function __construct(string $a, string $b = null)
    {
        $this->b = $b;
    }

    public function common($b, $c, $g = null): int
    {

    }

    private function subtract(int $a) : int
    {
        $this->total -= $a;
    }

    public function add(int $a): int
    {
        return $this->total += $a;
    }
}