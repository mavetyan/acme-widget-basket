<?php

declare(strict_types=1);

namespace AcmeWidget\Basket\Delivery;

/**
 * Represents a delivery pricing rule based on subtotal threshold.
 * If the subtotal is below the threshold, a charge is applied.
 */
final class DeliveryRule
{
    /**
     * @var float Subtotal threshold in dollars (e.g., 50.00 means "under $50")
     */
    private float $threshold;

    /**
     * @var float Delivery charge in dollars (e.g., 4.95)
     */
    private float $charge;

    /**
     * @param float $threshold The maximum subtotal this rule applies to
     * @param float $charge The delivery charge for this rule
     */
    public function __construct(float $threshold, float $charge)
    {
        $this->threshold = $threshold;
        $this->charge = $charge;
    }

    /**
     * Gets the threshold for this delivery rule.
     * @return float The threshold in dollars
     */
    public function getThreshold(): float
    {
        return $this->threshold;
    }

    /**
     * Gets the delivery charge for this rule.
     * @return float The charge in dollars
     */
    public function getCharge(): float
    {
        return $this->charge;
    }
}
