<?php

use Matchish\ExchangeRates;
use Codeception\Util\Stub;

class ExchangeRatesTest extends \Codeception\TestCase\Test
{

    use \Codeception\Specify;

    protected function _before()
    {
    }

    protected function _after()
    {
    }

    public function testInstance()
    {
        $this->specify("it should be instantiable", function () {
            new ExchangeRates();
        });
    }

    public function testImport()
    {
        $this->specify("должен бросать исключение если файлы импорта не существуют", function () {

            $er = new ExchangeRates();
            $er->parse('fail.file');

        }, ['throws' => 'PHPExcel_Reader_Exception']);

        $this->specify("должен бросать исключение если не возможно определить какие курсы валют в нем содержатся", function () {

            $er = new ExchangeRates();
            $er->parse($this->data('wrong.xls'));

        }, ['throws' => 'Matchish\Exceptions\ExchangeRatesParseException']);

        $this->specify("должен правильно определять тип курсов в файле", function ($filename, $parserClassName) {

            Stub::make($parserClassName, array('parse' => Stub::once(function () {
                return [];
            })));

            $er = new ExchangeRates();
            $er->parse($this->data($filename));

        }, ['examples' => [
            ['cash.xls', '\Matchish\ExchangeRatesCashParser'],
            ['card.xls', '\Matchish\ExchangeRatesCardParser'],
        ]]);

        $this->specify("должен принимать массив имен файлов", function () {

            $er = new ExchangeRates();
            $er->parse([$this->data('cash.xls'), $this->data('card.xls')]);

        });
    }

    public function data($filename)
    {
        return __DIR__ . '/../_data/' . $filename;
    }

}