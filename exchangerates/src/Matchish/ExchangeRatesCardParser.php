<?php
/**
 * Created by PhpStorm.
 * User: SergeyS
 * Date: 29.10.2015
 * Time: 16:24
 */

namespace Matchish;


class ExchangeRatesCardParser implements ExchangeRatesParserInterface
{

    protected $codes;

    /**
     * ExchangeRatesCardParser constructor.
     */
    public function __construct()
    {
        $this->codes = [
            'USD (840)' => '/USD\s*\(\s*840\s*\)/',
            'EUR (978)' => '/EUR\s*\(\s*978\s*\)/'];
    }

    public function parse(\PHPExcel_Worksheet $sheet)
    {
        $rates = [];

        foreach ($sheet->getRowIterator() as $row) {

            $rowIndex = $row->getRowIndex();

            $currency = $sheet->getCell('A' . $rowIndex)->getValue();

            if (!$this->isCurrency($currency)) {
                continue;
            }
            $date = new \DateTime();
            $rate = [
                'type' => 'card',
                'date' => $date->format('Y-m-d'),
                'curr' => $this->getCurrencyCode($currency),
                'count' => (int)$sheet->getCell('B' . $rowIndex)->getValue(),
                'buy' => number_format((float) $sheet->getCell('C' . $rowIndex)->getValue(), 2, '.', ''),
                'sale' => number_format((float) $sheet->getCell('D' . $rowIndex)->getValue(), 2, '.', ''),
                'nbu' => number_format((float) $sheet->getCell('E' . $rowIndex)->getValue(), 4, '.', '')
            ];

            $rates[] = $rate;
        }

        return $rates;
    }

    private function isCurrency($value)
    {
        foreach ($this->codes as $code) {
            if (!empty($this->getCurrencyCode($value))) {
                return true;
            }
        }
        return false;
    }

    private function getCurrencyCode($value)
    {
        foreach ($this->codes as $code => $pattern) {
            if (preg_match($pattern, $value)) {
                return $code;
            }
        }
        return null;
    }

}