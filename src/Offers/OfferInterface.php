<?php

declare(strict_types=1);

namespace AcmeWidget\Basket\Offers;

use AcmeWidget\Basket\Products\Product;
use AcmeWidget\Basket\Types\Money;

/**
 * OfferInterface: each offer returns the total discount (Money) for the given items.
 */
interface OfferInterface
{
    /**
     * Applies all configured offers to the given basket items.
     * @param Product[] $items
     */
    public function apply(array $items): Money;
}
