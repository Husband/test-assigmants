<?php
/**
 * Created by PhpStorm.
 * User: SergeyS
 * Date: 29.10.2015
 * Time: 16:26
 */

namespace Matchish;


interface ExchangeRatesParserInterface
{
    public function parse(\PHPExcel_Worksheet $sheet);
}