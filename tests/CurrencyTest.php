<?php
use PHPUnit\Framework\TestCase;
use Commission\Classes\Currency as Currency;

class CurrencyTest extends TestCase
{
    public function testUnknownCurreny()
    {
        $currency = new Currency();

        $this->assertEquals('Unknown currency.', $currency->convertToEUR('PHP', 100));
    }

    public function testConvert()
    {
        $currency = new Currency();

        $this->assertEquals(23160.66, $currency->convertToEUR('JPY', 3000000));
    }
}