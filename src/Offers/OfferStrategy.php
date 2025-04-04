<?php

declare(strict_types=1);

namespace AcmeWidget\Basket\Offers;

use AcmeWidget\Basket\Products\Product;
use AcmeWidget\Basket\Types\Money;

/**
 * Applies all available offers to the basket and calculates total discount.
 */
final class OfferStrategy
{
    /**
     * @var OfferInterface[] List of available offers to apply
     */
    private array $offers;

    /**
     * @param OfferInterface[] $offers Array of offer implementations
     */
    public function __construct(array $offers)
    {
        $this->offers = $offers;
    }

    /**
     * Applies all configured offers to the given basket items.
     * @param array $items List of products in the basket
     * @return Money Total discount as a Money object
     */
    public function applyOffers(array $items): Money
    {
        $totalDiscount = new Money(0);
        foreach ($this->offers as $offer) {
            $discount = $offer->apply($items);
            $totalDiscount = $totalDiscount->add($discount);
        }
        return $totalDiscount;
    }
}
