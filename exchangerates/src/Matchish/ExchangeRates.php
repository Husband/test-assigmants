<?php
/**
 * Created by PhpStorm.
 * User: SergeyS
 * Date: 28.10.2015
 * Time: 17:52
 */

namespace Matchish;


use Matchish\Exceptions\ExchangeRatesException;
use Matchish\Exceptions\ExchangeRatesParseException;

class ExchangeRates
{

    protected $rates;
    protected $db;

    /**
     * ExchangeRates constructor.
     * @param $services
     */
    public function __construct($db = null)
    {
        $this->db = $db;
    }


    public function parse($source)
    {

        $rates = [];

        if (!is_array($source)) {
            $source = [$source];
        }

        foreach ($source as $filename) {

            $excelReader = \PHPExcel_IOFactory::load($filename);

            $excelReader->setActiveSheetIndex(0);

            $activeSheet = $excelReader->getActiveSheet();

            $ratesType = $this->evaluateSourceType($activeSheet);

            if (!isset($ratesType)) {
                throw new ExchangeRatesParseException('Не могу определить тип курсов валют которые содержатся в файле ' . $filename);
            }

            $parser = $this->getParser($ratesType);

            $rates = array_merge($parser->parse($activeSheet), $rates);

        }

        $this->rates = $rates;

    }

    public function getRates()
    {
        return $this->rates;
    }

    /**
     * @param $aSheet
     * @return string
     */
    protected function evaluateSourceType($activeSheet)
    {
        $ratesType = null;
        //получим итератор строки и пройдемся по нему циклом
        foreach ($activeSheet->getRowIterator() as $row) {
            //получим итератор ячеек текущей строки
            $cellIterator = $row->getCellIterator();
            //пройдемся циклом по ячейкам строки
            foreach ($cellIterator as $cell) {
                switch ($cell->getCalculatedValue()) {
                    case 'Про встановлення курсів купівлі-продажу готівкової іноземної валюти';
                        $ratesType = 'cash';
                        continue;
                        break;
                    case 'Встановити курси конвертації іноземної валюти при списанні коштів з карткового рахунку не у валюті рахунку';
                        $ratesType = 'card';
                        continue;
                        break;
                }
            }
        }

        return $ratesType;
    }

    /**
     * @param $ratesType
     * @return ExchangeRatesCardParser|ExchangeRatesCashParser
     * @throws ExchangeRatesParseException
     */
    protected function getParser($ratesType)
    {
        switch ($ratesType) {
            case 'card';
                return new ExchangeRatesCardParser();
                break;
            case 'cash';
                return new ExchangeRatesCashParser();
                break;
            default;
                throw new ExchangeRatesParseException('Не знаю как парсить данные этого типа.');
                break;
        }
    }

    public function save()
    {
        foreach ($this->rates as $rate) {

            $this->deleteFromDB([
                'type' => $rate['type'],
                'date' => $rate['date'],
                'curr' => substr($rate['curr'], 0, 3)
            ]);
            $this->insertToDB($rate);

        }
    }

    private function deleteFromDB($values)
    {
        $db = $this->getDB();
        $predicatParts = [];
        foreach ($values as $attribute => $value) {
            $predicatParts[] = $attribute . '=' . '"' . $value . '"';
        }
        $predicat = implode(' AND ', $predicatParts);

        $sql="DELETE FROM t_exchange_rates WHERE " . $predicat;

        $db->query($sql);
    }

    private function insertToDB($values)
    {
        $db = $this->getDB();
        $attributes = ['date', 'curr', 'count', 'buy', 'sale', 'nbu', 'type', 'md5'];
        $attributesString = '`' . implode('`, `', $attributes) . '`';

        $sortedValues = [];
        foreach ($attributes as $attribute) {
            if ($attribute === 'md5') {
                continue;
            }
            $sortedValues[$attribute] = $values[$attribute];
        }
        $sortedValues['md5'] = md5(json_encode($sortedValues));

        $valuesString = '"' . implode('","', $sortedValues) . '"';
        $sql = 'INSERT INTO `t_exchange_rates`(' . $attributesString . ') VALUES (' . $valuesString . ')';

        $db->query($sql);

    }

    private function getDB()
    {
        if (isset($this->db)) {
            return $this->db;
        }
        throw new ExchangeRatesException('Database should be passed to constructor as service');
    }
}