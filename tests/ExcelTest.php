<?php
use PHPUnit\Framework\TestCase;
use Commission\Helpers\Excel as Excel;

class ExcelTest extends TestCase
{
    public function testReadCSV()
    {
        $filePath = 'transactions.csv';
        $excel = new Excel($filePath);

        $this->assertIsIterable($excel->read());        
    }

    public function testFileNotFound()
    {
        $filePath = 'file.csv';
        $excel = new Excel($filePath);

        $this->assertFalse($excel->read());        
    }

    public function testFileNotSupported()
    {
        $filePath = 'transactions.txt';
        $excel = new Excel($filePath);

        $this->assertFalse($excel->read());        
    }

    public function testGetCorrectFileExtension()
    {
        $filePath = 'transactions.csv';
        $excel = new Excel($filePath);

        $this->assertSame($excel->getFileExtension(), 'csv');        
    }
}