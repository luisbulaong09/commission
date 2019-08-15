<?php
use PHPUnit\Framework\TestCase;
use Commission\Classes\Validation as Validation;

class ValidationTest extends TestCase
{
    public function testValidData()
    {
        $validate = new Validation();
        $data = [
            '2016-02-19', 5, 'natural', 'cash_out', 3000000, 'JPY'
        ];
        $fields = [
            'date',
            'user_id',
            'user_type',
            'operation_type',
            'amount',
            'currency'
        ];

        $this->assertTrue($validate->validateTransactionData($data, $fields));
    }

    public function testInvalidData()
    {
        $validate = new Validation();
        $data = [
            '2016-02-19', 'ID5', 'natural', 'cash_out', 3000000, 'PHP'
        ];
        $fields = [
            'date',
            'user_id',
            'user_type',
            'operation_type',
            'amount',
            'currency'
        ];

        $this->assertFalse($validate->validateTransactionData($data, $fields));
    }

    public function testIncompleteData()
    {
        $validate = new Validation();
        $data = [
            '2016-02-19', 5, 'natural', 'cash_out', 3000000
        ];
        $fields = [
            'date',
            'user_id',
            'user_type',
            'operation_type',
            'amount',
            'currency'
        ];

        $this->assertFalse($validate->validateTransactionData($data, $fields));
    }
}