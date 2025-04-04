<?php

declare(strict_types=1);

namespace AcmeWidget\Basket\Tests\Offers;

use PHPUnit\Framework\TestCase;
use AcmeWidget\Basket\Offers\OfferStrategy;
use AcmeWidget\Basket\Offers\RedWidgetOffer;
use AcmeWidget\Basket\Products\Product;
use AcmeWidget\Basket\Types\Money;

final class OfferStrategyTest extends TestCase
{
    private OfferStrategy $strategy;

    protected function setUp(): void
    {
        $this->strategy = new OfferStrategy(
            [
                new RedWidgetOffer('R01', 0.5),
            ]
        );
    }

    public function testApplyStrategyWithKnownProduct(): void
    {
        // 2 x R01 => expect 50% off one => $16.48 discount
        $items = [
            new Product('R01', new Money(3295)),
            new Product('R01', new Money(3295)),
        ];

        $discount = $this->strategy->applyOffers($items);
        $this->assertEquals(16.48, $discount->toFloat());
    }

    public function testApplyStrategyWithUnknownProduct(): void
    {
        // No offers should apply for these items
        $items = [
            new Product('X99', new Money(1000)),
            new Product('X99', new Money(1000)),
        ];

        $discount = $this->strategy->applyOffers($items);
        $this->assertEquals(0.0, $discount->toFloat());
    }
}
