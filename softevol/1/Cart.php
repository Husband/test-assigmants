<?php

use Ruler\Context;
use Ruler\RuleBuilder;
use Ruler\Rule;
use Ruler\Operator\EqualTo;
use Ruler\Operator\LogicalOr;
use Ruler\Operator\LogicalAnd;
use Ruler\Variable;

class Cart
{
    protected $products = [];
    protected $productsTemp = [];
    protected $discount = 0;

    public function add($products)
    {
        if (!is_array($products)) {
            $products = [$products];
        }
        $this->products = array_merge($this->products, $products);
    }

    public function total()
    {
        $this->discount = 0;
        $this->productsTemp = $this->products;

        $this->executeSetRule(['A', 'B'], 10);
        $this->executeSetRule(['D', 'E'], 5);
        $this->executeSetRule(['E', 'F', 'G'], 5);
        $this->executeSetRule(['A', ['K', 'L', 'M']], 5);
        $this->executeProductsAmountRule();

        $summ = $this->summ();
        $total = $summ - $this->discount;

        return $total;
    }

    protected function executeSetRule($types, $discountPercent)
    {
        $contextOptions['true'] = true;
        $andOperators = [];
        $orOperators = [];
        $trueVar = new Variable('true');
        foreach ($types as $type) {
            if (is_array($type)) {
                foreach ($type as $t) {
                    $contextOptions['contains' . $t . 'Products'] = $this->containsProductWithType($t);
                    $orOperators[] = new EqualTo(new Variable('contains' . $t . 'Products'), $trueVar);
                }
                $andOperators[] = new LogicalOr($orOperators);
            } else {
                $contextOptions['contains' . $type . 'Products'] = $this->containsProductWithType($type);
                $var = new Variable('contains' . $type . 'Products');
                $andOperators[] = new EqualTo($var, $trueVar);
            }
        }
        $context = new Context($contextOptions);
        $rule = new Rule(
            new LogicalAnd($andOperators),
            function () use ($types, $discountPercent){
                $discount = 0;

                foreach ($types as $type) {
                    if (!is_array($type)) {
                        $extractTypes[] = $type;
                    }
                }

                while (true) {
                    $tuple = $this->extractProductsTuple($extractTypes);
                    if (empty($tuple)) {
                        break;
                    }
                    foreach ($tuple as $product) {
                        $discount += $product['price'] * $discountPercent / 100;
                    }
                }

                $this->discount += $discount;
            }
        );

        $rule->execute($context);

    }

    protected function executeProductsAmountRule()
    {

        $productsCount = $this->countProductsExcept(['A', 'C']);

        $total = $this->totalExcept(['A', 'C']);

        $discountPercent = 0;
        if ($productsCount == 3) {
            $discountPercent = 5;
        } else if ($productsCount == 4) {
            $discountPercent = 10;
        } else if ($productsCount > 5) {
            $discountPercent = 20;
        }
        $this->discount += $total * $discountPercent / 100;
    }

    protected function summ()
    {
        $summ = 0;
        foreach ($this->products as $product) {
            $summ += $product['price'];
        }
        return $summ;
    }

    protected function containsProductWithType($type)
    {
        $result = false;
        foreach ($this->products as $product) {
            if ($product['type'] == $type) {
                $result = true;
            }
        }
        return $result;
    }

    protected function extractProductsTuple($types)
    {
        $tuple = [];
        foreach ($types as $type) {
            foreach ($this->productsTemp as $key => $product) {
                if ($product['type'] == $type) {
                    $tuple[$key] = $product;
                    break;
                }
            }
        }

        if (count($tuple) != count($types)) {
            $tuple = [];
        }

        foreach ($tuple as $key => $product) {
            unset($this->productsTemp[$key]);
        }
        return $tuple;

    }

    private function countProductsExcept($types)
    {
        $count = 0;
        foreach ($this->productsTemp as $product) {
            if (!in_array($product['type'], $types)) {
                $count++;
            }
        }

        return $count;
    }

    private function totalExcept($types)
    {
        $total = 0;
        foreach ($this->productsTemp as $product) {

            if (!in_array($product['type'], $types)) {
                $total += $product['price'];
            }
        }
        return $total;
    }
}