#!/usr/bin/env php
<?php

declare(strict_types=1);

require __DIR__ . '/vendor/autoload.php';

use AcmeWidget\Basket\Basket;
use AcmeWidget\Basket\Products\Product;
use AcmeWidget\Basket\Products\ProductCatalogue;
use AcmeWidget\Basket\Types\Money;
use AcmeWidget\Basket\Delivery\DeliveryRule;
use AcmeWidget\Basket\Delivery\DeliveryChargeCalculator;
use AcmeWidget\Basket\Offers\OfferStrategy;
use AcmeWidget\Basket\Offers\RedWidgetOffer;

// 0) Parse CLI arguments (skip script name)
array_shift($argv);
if (empty($argv)) {
    echo "Usage: php index.php <PRODUCT_CODE> [<PRODUCT_CODE> ...]" . PHP_EOL;
    exit(1);
}

// 1) Create a product catalogue
$catalogue = new ProductCatalogue(
        [
            new Product('R01', new Money(3295)), // $32.95 in cents
            new Product('G01', new Money(2495)), // $24.95 in cents
            new Product('B01', new Money(795)),  // $7.95 in cents
        ]
);

// 2) Create a delivery calculator
$delivery = new DeliveryChargeCalculator(
        [
           new DeliveryRule(50.0, 4.95),
           new DeliveryRule(90.0, 2.95),
           // >= $90 => free
        ]
);

// 3) Create an offer strategy
$offers = new OfferStrategy(
        [
            new RedWidgetOffer('R01', 0.5),
        ]
);

// 4) Create the basket
$basket = new Basket($catalogue, $delivery, $offers);

// 5) Add items from CLI
foreach ($argv as $productCode) {
    try {
        $basket->add($productCode);
    } catch (Throwable $e) {
        echo "Error adding product '{$productCode}': {$e->getMessage()}" . PHP_EOL;
    }
}

// 6) Calculate total
$total = $basket->total();

// 7) Print result
echo "Basket: " . implode(', ', $argv) . PHP_EOL;
echo "Total: \$" . number_format($total, 2) . PHP_EOL;
