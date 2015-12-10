<?php


use Matchish\ExchangeRatesCardParser;

class ExchangeRatesCardParserTest extends \Codeception\TestCase\Test
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

            $excelReader = \PHPExcel_IOFactory::load(__DIR__ . '/../_data/card.xls');
            $excelReader->setActiveSheetIndex(0);

            $this->sheet = $excelReader->getActiveSheet();
            $this->rates = include __DIR__ . '/../_data/card_rates.php';

            $parser = new ExchangeRatesCardParser();

            $rates = $parser->parse($this->sheet);

            $this->assertEquals($this->rates, $rates);

        });
    }
}