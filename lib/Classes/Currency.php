<?php
namespace Commission\Classes;

class Currency
{
	/**
	* Currency echange rates
	*/
	protected static $exchangeRates = [
		'EUR_USD' => 1.1497,
		'EUR_JPY' => 129.53,
		'USD_EUR' => 0.87,
		'JPY_EUR' => 0.00772022
	];

	/**
	* Converts amount from a currency to another currency
	*
	* @param $from string
	* @param $target string
	* @param amount float 
	*
	* @return float
	*/
	public function convert($from, $target, $amount)
	{
		$exchangeRatesKey = $from.'_'.$target;

		if (array_key_exists($exchangeRatesKey, self::$exchangeRates)) {
			$rate = ($from != $target) ? self::$exchangeRates[$exchangeRatesKey] : 1;

			return $rate*$amount;
		} else {
			return 'Unknown currency.';
		}
	}
}
?>