<?php
/**
 * Created by PhpStorm.
 * User: SergeyS
 * Date: 29.10.2015
 * Time: 15:08
 */
$date = new \DateTime();

return [
    [
        'type' => 'card',
        'date' => $date->format('Y-m-d'),
        'curr' => 'USD (840)',
        'count' => 100,
        'buy' => '2250.00',
        'sale' => '2350.00',
        'nbu' => '2114.7443'
    ],
    [
        'type' => 'card',
        'date' => $date->format('Y-m-d'),
        'curr' => 'EUR (978)',
        'count' => 100,
        'buy' => '2500.00',
        'sale' => '2600.00',
        'nbu' => '2360.0546'
    ]
];