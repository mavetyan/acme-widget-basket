<?php

declare(strict_types=1);

namespace AcmeWidget\Basket\Delivery;

use AcmeWidget\Basket\Types\Money;

/**
 * Calculates delivery charges based on a set of threshold rules.
 */
final class DeliveryChargeCalculator implements DeliveryChargeCalculatorInterface
{
    /**
     * @var DeliveryRule[] List of delivery rules sorted by threshold
     */
    private array $rules;

    /**
     * @param DeliveryRule[] $rules Array of delivery rules (e.g., under $50 → $4.95)
     */
    public function __construct(array $rules)
    {
        $this->rules = $rules;
    }

    /**
     * Determines the delivery charge for a given subtotal.
     * Returns a Money object representing the delivery charge.
     * @param Money $subtotal Basket subtotal after discounts
     * @return Money Delivery charge as a Money object
     */
    public function getCharge(Money $subtotal): Money
    {
        foreach ($this->rules as $rule) {
            if ($subtotal->toFloat() < $rule->getThreshold()) {
                return new Money((int) round($rule->getCharge() * 100));
            }
        }

        return new Money(0); // Free shipping
    }
}
