<?php
namespace Commission\Classes;

use Commission\Classes\Commission as Commission;
use Commission\Classes\Currency as Currency;
use Commission\Classes\Transaction as Transaction;
use Commission\Classes\Validation as Validation;

class Calculation extends Commission
{
	private $currency = null;

	private $transaction = null;

	private $validate = null;

	public function __construct()
	{
		$this->currency = new Currency();
		$this->transaction = new Transaction();
		$this->validate = new Validation();
	}

	/**
	* Returns computed commission
	*
	* @param $transactionData | array
	*
	* @return float
	*/
	public function getCommissionFee($transactionData)
	{
		return $this->calculateCommissionFee($transactionData);
	}

	/**
	* Round up amount then formats to money
	*
	* @param $amount float
	* @param $precision int
	*
	* @return float
	*/
	public function formatMoney($amount, $precision)
	{
		$pow = pow (10, $precision); 
    	$roundedValue = (ceil( $pow * $amount ) + ceil($pow * $amount - ceil($pow * $amount))) / $pow;

    	return number_format($roundedValue, 2);
	}

	/**
	* Compute commission based on transaction type
	*
	* @param $transactionData array
	*
	* @return float
	*/
	private function calculateCommissionFee($transactionData)
	{
		$commissionFee = false;
		$fields = array_flip($this->requiredFields);
		if ($this->validate->validateTransactionData($transactionData, $this->requiredFields)) {
			if ($transactionData[$fields['operation_type']] == "cash_in") {
				$commissionFee = $this->calculateCashInFee($transactionData, $fields);
			} else {
				$commissionFee = $this->calculateCashOutFee($transactionData, $fields);
			}
		}

		return $commissionFee;
	}

	/**
	* Compute commission for cash in transactions
	*
	* @param $transactionData array
	* @param $fields array
	*
	* @return float
	*/
	private function calculateCashInFee($transactionData, $fields)
	{
		$amount = $transactionData[$fields['amount']];
		$currency = $transactionData[$fields['currency']];

		$calculatedFee = $amount * parent::$cashInPercentage;

		switch (strtoupper($currency)) {
			case 'USD':
				$calculatedFeeInEUR = $this->currency->convertToEUR('USD', $calculatedFee);
				$rawCommissionFee = ($calculatedFeeInEUR < 5) ? $calculatedFee : $this->currency->convertToEUR('USD', 5);
				$commissionFee = $this->formatMoney($rawCommissionFee, 2);	
				break;
			case 'JPY':
				$calculatedFeeInEUR = $this->currency->convertToEUR('JPY', $calculatedFee);
				$rawCommissionFee = ($calculatedFeeInEUR < 5) ? $calculatedFee : $this->currency->convertToEUR('JPY', 5);
				$commissionFee = ceil($rawCommissionFee);
				break;
			default:
				$rawCommissionFee = ($calculatedFee < 5) ? $calculatedFee : 5;
				$commissionFee = $this->formatMoney($rawCommissionFee, 2);
				break;
		}

		return $commissionFee;	
	}

	/**
	* Compute commission for cash out transactions
	*
	* @param $transactionData array
	* @param $fields array
	*
	* @return float
	*/
	private function calculateCashOutFee($transactionData, $fields)
	{
		$amount = $transactionData[$fields['amount']];
		$currency = $transactionData[$fields['currency']];
		$userType = $transactionData[$fields['user_type']];
		$isFreeCommissionFee = false;

		if (strtolower($userType) == 'natural') {
			$userId = $transactionData[$fields['user_id']];
			$date = $transactionData[$fields['date']];
			$transactionList = $this->transaction->getByKey($fields['user_id'], $userId);

			if (!empty($transactionList)) {
				$totalCashOut = 0;
				$totalCashOutCount = 0;
				foreach ($transactionList as $key => $transaction) {
					if (date('oW', strtotime($date)) == date('oW', strtotime($transaction[$fields['date']]))) {
						$transactionCurrency = $transaction[$fields['currency']];
						$amountWithdrew = $this->currency->convertToEUR($transactionCurrency, $transaction[$fields['amount']]);
						$totalCashOut = $totalCashOut + $amountWithdrew;
						$totalCashOutCount++;
					}
				}

				if (
					$totalCashOut < parent::$cashOutFreeCharge && 
					$totalCashOutCount < parent::$cashOutNumberOfDiscountedTransactionPerWeek
				) {
					$freeAmount = parent::$cashOutFreeCharge - $totalCashOut;
					$isFreeCommissionFee = (
						($freeAmount+$amount) <= parent::$cashOutFreeCharge || $amount <= $freeAmount
						) ? true : $isFreeCommissionFee;
					$amount = (!$isFreeCommissionFee) ? $amount - $freeAmount : $amount;
				}
			} else {
				$convertedAmount = $this->currency->convertToEUR($currency, $amount);
				$isFreeCommissionFee = ($convertedAmount <= parent::$cashOutFreeCharge) ? true : $isFreeCommissionFee;
				$amount = (!$isFreeCommissionFee) ? 
					$this->currency->convertToEUR($currency, ($convertedAmount-parent::$cashOutFreeCharge)) : 
					$amount;
			}

			$calculatedFee = floatval($amount) * parent::$cashOutPercentage;

			$this->transaction->insert($transactionData);

			return $this->convertCashOutCommissionFee($calculatedFee, $currency, $userType, $isFreeCommissionFee);
		} else {
			$calculatedFee = $amount * parent::$cashOutPercentage;

			return $this->convertCashOutCommissionFee($calculatedFee, $currency, $userType);
		}
	}

	/**
	* Converts calculated commission fee to currency
	*
	* @param $calculatedFee float
	* @param $currency string
	* @param $userType string
	* @param $noCommissionFee boolean
	*
	* @return float
	*/
	private function convertCashOutCommissionFee($calculatedFee, $currency, $userType, $noCommissionFee=false)
	{
		$isLegal = $userType == 'legal';
		switch (strtoupper($currency)) {
			case 'USD':
				$calculatedFeeInEUR = $this->currency->convertToEUR('USD', $calculatedFee);
				$rawCommissionFee = $calculatedFee;
				if ($isLegal) {
					$rawCommissionFee = ($calculatedFeeInEUR > 0.5) ? $calculatedFee : $this->currency->convertToEUR('USD', 0.5);
				}
				$rawCommissionFee = (!$noCommissionFee) ? $rawCommissionFee : 0;
				$commissionFee = $this->formatMoney($rawCommissionFee, 2);
				break;
			case 'JPY':
				$calculatedFeeInEUR = $this->currency->convertToEUR('JPY', $calculatedFee);
				$rawCommissionFee = $calculatedFee;
				if ($isLegal) {
					$rawCommissionFee = ($calculatedFeeInEUR > 0.5) ? $calculatedFee : $this->currency->convertToEUR('JPY', 0.5);
				}
				$rawCommissionFee = (!$noCommissionFee) ? $rawCommissionFee : 0;
				$commissionFee = ceil($rawCommissionFee);
				break;
			default:
				$rawCommissionFee = $calculatedFee;
				if ($isLegal) {
					$rawCommissionFee = ($calculatedFee > 0.5) ? $calculatedFee : 0.5;
				}
				$rawCommissionFee = (!$noCommissionFee) ? $rawCommissionFee : 0;
				$commissionFee = $this->formatMoney($rawCommissionFee, 2);
				break;
		}

		return $commissionFee;
	}
}
?>