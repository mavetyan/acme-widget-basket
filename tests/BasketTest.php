<?php

declare(strict_types=1);

namespace AcmeWidget\Basket\Tests;

use PHPUnit\Framework\TestCase;
use AcmeWidget\Basket\Basket;
use AcmeWidget\Basket\BasketInterface;
use AcmeWidget\Basket\Products\Product;
use AcmeWidget\Basket\Products\ProductCatalogue;
use AcmeWidget\Basket\Types\Money;
use AcmeWidget\Basket\Delivery\DeliveryRule;
use AcmeWidget\Basket\Delivery\DeliveryChargeCalculator;
use AcmeWidget\Basket\Offers\RedWidgetOffer;
use AcmeWidget\Basket\Offers\OfferStrategy;

final class BasketTest extends TestCase
{
    private BasketInterface $basket;

    protected function setUp(): void
    {
        $catalogue = new ProductCatalogue(
            [
                new Product('R01', new Money(3295)), // $32.95
                new Product('G01', new Money(2495)), // $24.95
                new Product('B01', new Money(795)),  // $7.95
            ]
        );

        // Under $50 => 4.95, under $90 => 2.95, else free
        $delivery = new DeliveryChargeCalculator(
            [
                new DeliveryRule(50.0, 4.95),
                new DeliveryRule(90.0, 2.95),
            ]
        );

        $offers = new OfferStrategy(
            [
                new RedWidgetOffer('R01', 0.5),
            ]
        );

        $this->basket = new Basket($catalogue, $delivery, $offers);
    }

    public function testBasketB01G01(): void
    {
        // B01 ($7.95) + G01 ($24.95) = $32.90 => shipping 4.95 => total 37.85
        $this->basket->add('B01');
        $this->basket->add('G01');
        $this->assertEquals(37.85, $this->basket->total());
    }

    public function testBasketR01R01(): void
    {
        // 2 x $32.95 = 65.90
        // Offer: second R01 is half price => discount 16.48
        // So new total = 49.42 and as it is < 50, the shipping cost is 4.95
        // I.e. total is 54.37
        //
        $this->basket->add('R01');
        $this->basket->add('R01');
        $this->assertEquals(54.37, $this->basket->total());
    }

    public function testBasketR01G01(): void
    {
        // R01 ($32.95) + G01 ($24.95) = $57.90 => shipping 2.95 => total 60.85
        $this->basket->add('R01');
        $this->basket->add('G01');
        $this->assertEquals(60.85, $this->basket->total());
    }

    public function testBasketB01B01R01R01R01(): void
    {
        // B01 ($7.95), B01 ($7.95), R01 ($32.95), R01 ($32.95), R01 ($32.95)
        // Subtotal = 7.95 + 7.95 + 32.95 + 32.95 + 32.95 = 114.75
        // Red widget offer: 1 pair discount => 16.48, second pair => 16.48 (because 3 R01 => 1 pair discounted, 1 leftover)
        // Actually we have 3 red widgets => that means 1 pair only.
        // So total discount = 16.48
        // Subtotal after discount = 98.27
        // Shipping is over $90 => free => 0
        // Final = 98.27
        $this->basket->add('B01');
        $this->basket->add('B01');
        $this->basket->add('R01');
        $this->basket->add('R01');
        $this->basket->add('R01');
        $this->assertEquals(98.27, $this->basket->total());
    }
}
