<?php

namespace FunctionalTests;

use Matchish\ExchangeRates;

include_once 'DbiTester.php';

class ExchangeRatesTest extends \Codeception\TestCase\Test
{

    use \Codeception\Specify;

    /**
     * @var \FunctionalTester
     */
    protected $tester;

    protected function _before()
    {
    }

    protected function _after()
    {
    }

    // tests
    public function testSaveRates()
    {

        $this->specify("должен удалять из базы курсы с тем же типом операции, типом валют и датой что и сохраняемые", function () {

            $rates = include __DIR__ . '/../_data/card_rates.php';
            $date = new \DateTime();
            $oldRate = [
                'type' => 'card',
                'date' => $date->format('Y-m-d'),
                'curr' => 'USD',
                'count' => 100,
                'buy' => '9999.99',
                'sale' => '8888.88',
                'nbu' => '7777.7777'
            ];
            $hash = md5(json_encode($oldRate));
            $oldRate['md5'] = $hash;

            $I = $this->tester;

            $I->haveInDatabase('t_exchange_rates', $oldRate);

            $dbh = $this->getModule('Db')->dbh;
            $db = new DbiTester($dbh);

            $er = new ExchangeRates($db);
            $er->parse($this->data('card.xls'));
            $er->save();

            $I->dontSeeInDatabase('t_exchange_rates', $oldRate);

        });

        $this->specify("должен сохранять курсы валют в базу", function () {

            $rates = include __DIR__ . '/../_data/card_rates.php';

            $I = $this->tester;

            $dbh = $this->getModule('Db')->dbh;
            $db = new DbiTester($dbh);

            $er = new ExchangeRates($db);
            $er->parse($this->data('card.xls'));
            $er->save();

            $I->seeInDatabase('t_exchange_rates', array_pop($rates));

        });

    }

    public function data($filename)
    {
        return __DIR__ . '/../_data/' . $filename;
    }

}