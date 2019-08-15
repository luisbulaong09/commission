<?php
use PHPUnit\Framework\TestCase;
use Commission\Classes\Currency as Currency;

class CurrencyTest extends TestCase
{
    public function testUnknownCurreny()
    {
        $currency = new Currency();

        $this->assertEquals('Unknown currency.', $currency->convert('PHP', 'EUR', 100));
    }

    public function testConvert()
    {
        $currency = new Currency();

        $this->assertEquals(23160.66, $currency->convert('JPY', 'EUR', 3000000));
    }
}