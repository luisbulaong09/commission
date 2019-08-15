<?php
namespace Commission\Classes;

class Validation
{
	/**
	* Validation rules
	*/
	private $rules = [
		'date' => 'date',
		'user_id' => 'numeric',
		'user_type' => [
			'natural',
			'legal'
		],
		'operation_type' => [
			'cash_in',
			'cash_out'
		],
		'amount' => 'numeric',
		'currency' => [
			'EUR',
			'USD',
			'JPY'
		]
	];

	/**
	* Validate given transaction data
	*
	* @param $transactionData array
	* @param $requiredFields array
	*
	* @return boolean
	*/
	public function validateTransactionData($transactionData, $requiredFields)
	{
		$isValid = true;
		$validatedFields = [];
		
		foreach ($transactionData as $index => $value) {
			$key = $requiredFields[$index];
			if (is_array($this->rules[$key])) {
				if (!(in_array($value, $this->rules[$key]))) {
					$isValid = false;
				}
			} elseif ($this->rules[$key] == 'date') {
				if (!(strtotime($value))) {
					$isValid = false;
				}
			} elseif ($this->rules[$key] == 'numeric') {
				if (!(is_numeric($value))) {
					$isValid = false;
				}
			}

			array_push($validatedFields, $key);
		}

		$validFields = array_intersect($validatedFields, $requiredFields);
		if (count($validFields) != count($requiredFields)) {
			$isValid = false;
		}

		return $isValid;
	}
}
?>