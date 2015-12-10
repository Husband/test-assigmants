<?php


use Matchish\ExchangeRates;

class JobTest extends \Codeception\TestCase\Test
{
    /**
     * @var \UnitTester
     */
    protected $tester;

    protected function _before()
    {
    }

    protected function _after()
    {
    }

    // tests
    public function testInstance()
    {
        $user = new ExchangeRates();
    }

}