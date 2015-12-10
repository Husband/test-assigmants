<?php


use Matchish\ExchangeRatesCashParser;

class ExchangeRatesCashParserTest extends \Codeception\TestCase\Test
{
    use \Codeception\Specify;

    protected $sheet;
    protected $rates;

    protected function _before()
    {
    }

    protected function _after()
    {
    }

    public function testParse()
    {
        $this->specify("должен возращать массив курсов валют", function () {

            $excelReader = \PHPExcel_IOFactory::load(__DIR__ . '/../_data/cash.xls');
            $excelReader->setActiveSheetIndex(0);

            $this->sheet = $excelReader->getActiveSheet();
            $this->rates = include __DIR__ . '/../_data/cash_rates.php';

            $parser = new ExchangeRatesCashParser();

            $rates = $parser->parse($this->sheet);

            $this->assertEquals($this->rates, $rates);

        });
    }
}