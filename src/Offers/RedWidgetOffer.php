<?php

declare(strict_types=1);

namespace AcmeWidget\Basket\Offers;

use AcmeWidget\Basket\Products\Product;
use AcmeWidget\Basket\Types\Money;

/**
 * "Buy one red widget (R01), get the second half price"
 */
final class RedWidgetOffer implements OfferInterface
{
    /**
     * @var string Product code the offer applies to (e.g. "R01")
     */
    private string $redWidgetCode;

    /**
     * @var float Discount factor for the second item (e.g. 0.5 means 50% off)
     */
    private float $discountFactor;

    /**
     * @param string $redWidgetCode
     * @param float $discountFactor
     */
    public function __construct(string $redWidgetCode = 'R01', float $discountFactor = 0.5)
    {
        $this->redWidgetCode = $redWidgetCode;
        $this->discountFactor = $discountFactor;
    }

    /**
     * Applies the offer to the basket items and returns the total discount.
     * @param array $items
     * @return Money
     */
    public function apply(array $items): Money
    {
        $countReds = 0;
        $unitPrice = null;

        foreach ($items as $item) {
            if ($item->getCode() === $this->redWidgetCode) {
                $countReds++;
                $unitPrice = $item->getPrice();
            }
        }

        if ($countReds < 2 || $unitPrice === null) {
            return new Money(0);
        }

        $pairs = intdiv($countReds, 2);
        $discountAmount = (int) round($unitPrice->getAmount() * $this->discountFactor * $pairs);

        return new Money($discountAmount, $unitPrice->getCurrency());
    }

}
