<?php
use PHPUnit\Framework\TestCase;
use Commission\Classes\Calculation as Calculation;

class CalculationTest extends TestCase
{
    public function testCorrectCommissionFee()
    {
        $calculation = new Calculation();

        $data = [
            '2014-12-31', 4, 'natural', 'cash_out', 1200.00, 'EUR'
        ];

        $this->assertEquals(0.60, $calculation->getCommissionFee($data));
    }
}