<?php

declare(strict_types=1);

namespace AcmeWidget\Basket\Tests\Offers;

use PHPUnit\Framework\TestCase;
use AcmeWidget\Basket\Offers\RedWidgetOffer;
use AcmeWidget\Basket\Products\Product;
use AcmeWidget\Basket\Types\Money;

final class RedWidgetOfferTest extends TestCase
{
    public function testApplyOfferOnOneItem(): void
    {
        $offer = new RedWidgetOffer('R01', 0.5);

        $items = [
            new Product('R01', new Money(3295)),
        ];

        $discount = $offer->apply($items);
        $this->assertEquals(0.0, $discount->toFloat());
    }

    public function testApplyOfferOnTwoItems(): void
    {
        $offer = new RedWidgetOffer('R01', 0.5);

        $items = [
            new Product('R01', new Money(3295)),
            new Product('R01', new Money(3295)),
        ];

        $discount = $offer->apply($items);
        $this->assertEquals(16.48, $discount->toFloat());
    }

    public function testApplyOfferOnThreeItems(): void
    {
        $offer = new RedWidgetOffer('R01', 0.5);

        $items = [
            new Product('R01', new Money(3295)),
            new Product('R01', new Money(3295)),
            new Product('R01', new Money(3295)),
        ];

        $discount = $offer->apply($items);
        $this->assertEquals(16.48, $discount->toFloat());
    }

    public function testApplyOfferOnFourItems(): void
    {
        $offer = new RedWidgetOffer('R01', 0.5);

        $items = [
            new Product('R01', new Money(3295)),
            new Product('R01', new Money(3295)),
            new Product('R01', new Money(3295)),
            new Product('R01', new Money(3295)),
        ];

        $discount = $offer->apply($items);
        $this->assertEquals(32.95, $discount->toFloat());
    }

    public function testOfferNotAppliedToDifferentProduct(): void
    {
        $offer = new RedWidgetOffer('R01', 0.5);

        $items = [
            new Product('G01', new Money(2495)),
            new Product('B01', new Money(795)),
        ];

        $discount = $offer->apply($items);
        $this->assertEquals(0.0, $discount->toFloat());
    }
}
