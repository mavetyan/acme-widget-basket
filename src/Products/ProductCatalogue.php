<?php

declare(strict_types=1);

namespace AcmeWidget\Basket\Products;

use AcmeWidget\Basket\Exceptions\ProductNotFoundException;

/**
 * A catalogue of available products, accessible by product code.
 */
final class ProductCatalogue
{
    /**
     * @var Product[] Indexed list of products by code
     */
    private array $products = [];

    /**
     * @param Product[] $products List of products to include in the catalogue
     */
    public function __construct(array $products)
    {
        foreach ($products as $product) {
            $this->products[$product->getCode()] = $product;
        }
    }

    /**
     * Fetch a product by its code.
     * @param string $code
     * @return Product
     * @throws ProductNotFoundException
     */
    public function getProductByCode(string $code): Product
    {
        if (!isset($this->products[$code])) {
            throw new ProductNotFoundException("Product {$code} not found");
        }
        return $this->products[$code];
    }
}
