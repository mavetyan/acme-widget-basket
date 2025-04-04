<?php

declare(strict_types=1);

namespace AcmeWidget\Basket\Tests\Products;

use PHPUnit\Framework\TestCase;
use AcmeWidget\Basket\Products\ProductCatalogue;
use AcmeWidget\Basket\Products\Product;
use AcmeWidget\Basket\Types\Money;
use AcmeWidget\Basket\Exceptions\ProductNotFoundException;

final class ProductCatalogueTest extends TestCase
{
    private ProductCatalogue $catalogue;

    protected function setUp(): void
    {
        $this->catalogue = new ProductCatalogue(
            [
                new Product('R01', new Money(3295)),
                new Product('B01', new Money(795)),
            ]
        );
    }

    public function testFindKnownProduct(): void
    {
        $product = $this->catalogue->getProductByCode('B01');
        $this->assertEquals('B01', $product->getCode());
        $this->assertEquals(7.95, $product->getPrice()->toFloat());
    }

    public function testFindUnknownProductThrowsException(): void
    {
        $this->expectException(ProductNotFoundException::class);
        $this->catalogue->getProductByCode('XYZ');
    }
}
