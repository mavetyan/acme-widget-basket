<?php

declare(strict_types=1);

namespace AcmeWidget\Basket\Products;

use AcmeWidget\Basket\Types\Money;

/**
 * Product has unique code and a price.
 */
final class Product
{
    /**
     * @var string Unique product code (e.g. "R01")
     */
    private string $code;

    /**
     * @var Money Product price as a Money value object
     */
    private Money $price;

    /**
     * @param string $code
     * @param Money $price
     */
    public function __construct(string $code, Money $price)
    {
        $this->code = $code;
        $this->price = $price;
    }

    /**
     * Returns the product code.
     * @return string
     */
    public function getCode(): string
    {
        return $this->code;
    }

    /**
     * Returns the product price.
     * @return Money
     */
    public function getPrice(): Money
    {
        return $this->price;
    }
}
