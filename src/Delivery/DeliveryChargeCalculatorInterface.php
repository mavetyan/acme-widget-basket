<?php

declare(strict_types=1);

namespace AcmeWidget\Basket\Delivery;

use AcmeWidget\Basket\Types\Money;

/**
 * An interface for all classes that calculate delivery cost (in dollars).
 */
interface DeliveryChargeCalculatorInterface
{
    /**
     * Returns a Money object representing the delivery charge.
     * @param Money $subtotal
     * @return Money
     */
    public function getCharge(Money $subtotal): Money;
}
