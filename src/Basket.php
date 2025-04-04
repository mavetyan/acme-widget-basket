<?php

declare(strict_types=1);

namespace AcmeWidget\Basket;

use AcmeWidget\Basket\Products\Product;
use AcmeWidget\Basket\Products\ProductCatalogue;
use AcmeWidget\Basket\Delivery\DeliveryChargeCalculatorInterface;
use AcmeWidget\Basket\Offers\OfferStrategy;
use AcmeWidget\Basket\Types\Money;

/**
 * Represents a shopping basket with products, offers, and delivery calculation.
 */
final class Basket implements BasketInterface
{
    /**
     * @var ProductCatalogue Product catalogue used to resolve product codes
     */
    private ProductCatalogue $catalogue;

    /**
     * @var DeliveryChargeCalculatorInterface Delivery pricing strategy
     */
    private DeliveryChargeCalculatorInterface $deliveryStrategy;

    /**
     * @var OfferStrategy Discount offers to apply
     */
    private OfferStrategy $offerStrategy;

    /**
     * @var Product[] List of added products
     */
    private array $items = [];

    /**
     * @param ProductCatalogue $catalogue
     * @param DeliveryChargeCalculatorInterface $deliveryStrategy
     * @param OfferStrategy $offerStrategy
     */
    public function __construct(
        ProductCatalogue $catalogue,
        DeliveryChargeCalculatorInterface $deliveryStrategy,
        OfferStrategy $offerStrategy
    ) {
        $this->catalogue       = $catalogue;
        $this->deliveryStrategy = $deliveryStrategy;
        $this->offerStrategy    = $offerStrategy;
    }

    /**
     * Adds a product to the basket by product code.
     * @param string $productCode
     * @return void
     * @throws Exceptions\ProductNotFoundException
     */
    public function add(string $productCode): void
    {
        $product = $this->catalogue->getProductByCode($productCode);
        $this->items[] = $product;
    }

    /**
     * Calculates the total price after discounts and delivery charges.
     * @return float
     */
    public function total(): float
    {
        // 1) Calculate subtotal
        $subtotal = new Money(0);
        foreach ($this->items as $item) {
            $subtotal = $subtotal->add($item->getPrice());
        }

        // 2) Calculate discount from offers
        $discount = $this->offerStrategy->applyOffers($this->items);

        // 3) Subtotal after discount
        $subtotalAfterDiscount = $subtotal->subtract($discount);

        // 4) Calculate delivery cost as Money object
        $deliveryCostMoney = $this->deliveryStrategy->getCharge($subtotalAfterDiscount);

        // 5) Final total = discounted subtotal + delivery cost
        $final = $subtotalAfterDiscount->add($deliveryCostMoney);

        // 6) Convert to float with two decimals
        return round($final->toFloat(), 2);
    }
}
