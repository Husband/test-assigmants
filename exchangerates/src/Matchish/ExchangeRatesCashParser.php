<?php
/**
 * Created by PhpStorm.
 * User: SergeyS
 * Date: 29.10.2015
 * Time: 16:25
 */

namespace Matchish;


class ExchangeRatesCashParser implements ExchangeRatesParserInterface
{
    protected $codes;

    /**
     * ExchangeRatesCashParser constructor.
     */
    public function __construct()
    {
        $this->codes = [
            'USD (840)',
            'EUR (978)',
            'RUB (643)',
            'CHF (756)',
            'GBP (826)',
            'CAD (124)',
            'PLN (985)'];
    }

    public function parse(\PHPExcel_Worksheet $sheet)
    {
        $rates = [];
        foreach ($sheet->getRowIterator() as $row) {

            $rowIndex = $row->getRowIndex();

            $currencyCode = $sheet->getCell('B' . $rowIndex)->getCalculatedValue();

            if (!$this->isCurrencyCode($currencyCode)) {
                continue;
            }
            $date = new \DateTime();
            $rate = [
                'type' => 'cash',
                'date' => $date->format('Y-m-d'),
                'curr' => $currencyCode,
                'count' => (int)$sheet->getCell('C' . $rowIndex)->getValue(),
                'buy' => number_format((float) $sheet->getCell('D' . $rowIndex)->getValue(), $this->getDecimals($currencyCode), '.', ''),
                'sale' => number_format((float) $sheet->getCell('E' . $rowIndex)->getValue(), $this->getDecimals($currencyCode), '.', ''),
                'nbu' => number_format((float) $sheet->getCell('F' . $rowIndex)->getValue(), $this->getDecimals($currencyCode, 'nbu'), '.', '')
            ];

            $rates[] = $rate;
        }
        return $rates;
    }

    private function isCurrencyCode($value)
    {
        return in_array($value, $this->codes);
    }

    private function getDecimals($currencyCode, $cellType = '')
    {
        if ('RUB (643)' == $currencyCode || $cellType == 'nbu') {
            return 4;
        } else {
            return 2;
        }
    }
}