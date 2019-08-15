<?php
use PHPUnit\Framework\TestCase;
use Commission\Classes\Calculation as Calculation;

class CalculationTest extends TestCase
{
    public function testCorrectCommissionFee()
    {
        $calculation = new Calculation();

        $data = [
            '2016-02-19', 5, 'natural', 'cash_out', 3000000, 'JPY'
        ];

        $this->assertEquals(8612, $calculation->getCommissionFee($data));
    }
}