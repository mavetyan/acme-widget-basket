<?php

declare(strict_types=1);

namespace AcmeWidget\Basket;

/**
 * Shopping basket interface
 */
interface BasketInterface
{
    /**
     * Adds a product to the basket by its code.
     * @param string $productCode
     * @return void
     */
    public function add(string $productCode): void;

    /**
     * Calculates the total price including offers and delivery.
     * @return float
     */
    public function total(): float;
}
