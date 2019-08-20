<?php
require 'bootstrap.php';

use Commission\Helpers\Excel as Excel;

if (isset($argv[1])) {
	$excel = new Excel($argv[1]);

	$fileExtension = $excel->getFileExtension();
	if ($fileExtension == 'csv') {
		$transactions = $excel->read();

		if ($transactions) {
			$calculate = new \Commission\Classes\Calculation();

			foreach ($transactions as $transaction_key => $transaction_data) {
				echo $calculate->getCommissionFee($transaction_data).PHP_EOL;
			}
		} else {
			echo 'File not found.';
		}
	} else {
		if (!$fileExtension) {
			echo 'File not found.';
		} else {
			echo 'File type not supported.';
		}
	}
}

?>