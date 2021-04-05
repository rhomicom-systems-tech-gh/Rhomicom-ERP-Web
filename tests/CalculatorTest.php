<?php

use \PHPUnit\Framework\TestCase;
use App\Calculator;

class CalculatorTest extends TestCase
{
    private $calculator;

    public function setup(): void
    {
        $this->calculator = new Calculator;
    }

    public function testAdd()
    {
        $this->setup();
        //$this->calculator = new Calculator;
        $oprnds=array(7,46);
        //print([7, 45]);
        $this->calculator->setOperands($oprnds);
        $this->assertEquals(53, $this->calculator->add());
    }
}
