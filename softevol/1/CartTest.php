<?php
require_once  __DIR__ . '/vendor/autoload.php';

class CartTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @dataProvider containsData
     */
    public function testTotal($products, $total)
    {
        $cart = new \Cart();
        $cart->add($products);
        $this->assertEquals($cart->total(), $total);
    }

    public function containsData()
    {
        $productTypes = explode(',', 'A,B,C,D,E,F,G,H,I,J,K,L,M');
        foreach ($productTypes as $type) {
            $var = strtolower($type);
            $$var = [
                'type' => $type,
                'price' => 100
            ];
        }

        return [
            [[$b, $a, $c], 280],
            [[$a, $a, $b, $c, $a, $b], 560],
            [[$a, $d, $b, $a, $b], 460],
            [[$a, $d, $e, $e, $d, $b], 560],
            [[$a, $d, $e, $e, $d, $b, $e], 660],
            [[$e, $f, $g], 285],
            [[$e, $f, $g, $d], 390],
            [[$a, $k], 195],
            [[$a, $m], 195],
            [[$a, $b, $k], 280],
            [[$a, $b, $k, $k, $k], 465],
            [[$a, $b, $c, $d, $f, $g, $l], 640]
        ];
    }
}
