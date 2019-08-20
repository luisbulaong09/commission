<?php
namespace Commission\Classes;

class Currency
{
	/**
	* Currency exchange rates to EUR
	*/
	protected static $exchangeRates = [
		'EUR' => 1,
		'USD' => 1.1497,
		'JPY' => 129.53,
		//'USD_EUR' => 0.87,
		//'JPY_EUR' => 0.00772022
	];

	/**
	* Converts amount from a currency to another currency
	*
	* @param $currency string
	* @param $amount float 
	*
	* @return float
	*/
	public function convertToEUR($currency, $amount)
	{
		echo $amount;
		if (array_key_exists($currency, self::$exchangeRates)) {
			if (self::$exchangeRates[$currency] > self::$exchangeRates['EUR']) {
				return self::$exchangeRates[$currency] / $amount;
			} else {
				return self::$exchangeRates[$currency] * $amount;
			}
		} else {
			return 'Unknown currency '.$currency;
		}
	}
}
?>