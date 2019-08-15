<?php
namespace Commission\Helpers;

class Excel
{
	/**
	* File path
	*/
	private $file_name = null;

	function __construct($file_name)
	{
	    $this->file_name = $file_name;
	}

	/**
	* Read and retrieve file records
	*
	* @return mixed
	*/
	public function read()
	{
		if (file_exists($this->file_name)) {
			switch ($this->getFileExtension()) {
				case 'csv':
					$data = array_map('str_getcsv', file($this->file_name));
					break;
				default:
					$data = [];
					break;
			}

			return $data;
		} else {
			return false;
		}
	}

	/**
	* Get file extension
	*
	* @return string
	*/
	public function getFileExtension()
	{
		$file_details = pathinfo($this->file_name);

		return $file_details['extension'];
	}
}
?>