<?php
namespace Commission\Classes;

class Commission
{
	/**
	* List of required fields
	*/
	protected $requiredFields = [
		'date',
		'user_id',
		'user_type',
		'operation_type',
		'amount',
		'currency'
	];

	/**
	* Cash in percentage 0.03% 
	*/
	protected static $cashInPercentage = 0.0003;

	/**
	* Cash in percentage 0.3% 
	*/
	protected static $cashOutPercentage = 0.003;

	/**
	* Free charge 1000 EUR
	*/
	protected static $cashOutFreeCharge = 1000;

	/**
	* Number of discounted transactions per week
	*/
	protected static $cashOutNumberOfDiscountedTransactionPerWeek = 3;

	/**
	* Maximum cash in commission fee in EUR
	*/
	protected static $maxCashInCommissionFee = 5;

	/**
	* Minimum cash out commission fee for legal person
	*/
	protected static $minCashOutCommissionFeeForLegalPerson = 0.5;
}
?>