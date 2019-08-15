<?php
namespace Commission\Classes;

class Transaction
{
	/**
	* List of performed transactions
	*/
	private $naturalCashOutTransactions = [];

	/**
	* Insert transaction record to list of transactions
	*/
	public function insert($transactionData)
	{
		array_push($this->naturalCashOutTransactions, $transactionData);
	}

	/**
	* Search and retrieve transactions based on given key
	* @return array
	*/
	public function getByKey($key, $value)
	{
		$transactionList = [];

		foreach ($this->naturalCashOutTransactions as $index => $transaction) {
			if ($transaction[$key] == $value) { 
				array_push($transactionList, $transaction);
			}
		}

		return $transactionList;
	}
}
?>