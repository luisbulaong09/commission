<?php
namespace Commission\Classes;

class Currency
{
	/**
	* Currency exchange rates
	*/
	protected static $exchangeRates = [
		'EUR' => 1,
		'USD' => 1.1497,
		'JPY' => 129.53
	];

	/**
	* Converts amount from a currency to another currency
	*
	* @param $from string
	* @param $to string
	* @param amount float 
	*
	* @return float
	*/
	public function convert($from, $to, $amount)
	{
		$from = strtoupper($from);
		$to = strtoupper($to);

		if ($this->isCurrenciesInList([$from, $to])) {
			$rate = $this->getRate($from, $to);

			return $rate * $amount;
		} else {
			return 'Unknown currency.';
		}
	}


	/**
	* Calculate conversion rate of 2 currencies
	*
	* @param $from string
	* @param $to string
	*
	* @return float
	*/
	private function getRate($from, $to)
	{
		$fromRate = self::$exchangeRates[$from];
		$toRate = self::$exchangeRates[$to];
		$rate = $toRate;

		if ($fromRate > $toRate) {
			$rate = $toRate / $fromRate;
		}

		return $rate;
	}


	/**
	* Check if currencies are present in list
	*
	* @param $from string
	* @param $target string
	*
	* @return boolean
	*/
	private function isCurrenciesInList($currencies)
	{
		$result = true;

		foreach ($currencies as $key => $currency) {
			if(!array_key_exists($currency, self::$exchangeRates)) {
				$result = false;
			}
		}

		return $result;
	}
}
?>