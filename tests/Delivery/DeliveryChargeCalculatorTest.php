<?php

declare(strict_types=1);

namespace AcmeWidget\Basket\Tests\Delivery;

use PHPUnit\Framework\TestCase;
use AcmeWidget\Basket\Types\Money;
use AcmeWidget\Basket\Delivery\DeliveryRule;
use AcmeWidget\Basket\Delivery\DeliveryChargeCalculator;

final class DeliveryChargeCalculatorTest extends TestCase
{
    private DeliveryChargeCalculator $calculator;

    protected function setUp(): void
    {
        $this->calculator = new DeliveryChargeCalculator(
            [
                new DeliveryRule(50.0, 4.95),
                new DeliveryRule(90.0, 2.95),
            ]
        );
    }

    public function testUnder50Applies495Fee(): void
    {
        $subtotal = new Money(4990); // $49.90
        $charge = $this->calculator->getCharge($subtotal);

        $this->assertEquals(4.95, $charge->toFloat());
    }

    public function testBetween50And90Applies295Fee(): void
    {
        $subtotal = new Money(7500); // $75.00
        $charge = $this->calculator->getCharge($subtotal);

        $this->assertEquals(2.95, $charge->toFloat());
    }

    public function testOver90IsFree(): void
    {
        $subtotal = new Money(9200); // $92.00
        $charge = $this->calculator->getCharge($subtotal);

        $this->assertEquals(0.0, $charge->toFloat());
    }

    public function testExactly50FallsIntoSecondRule(): void
    {
        $subtotal = new Money(5000); // $50.00
        $charge = $this->calculator->getCharge($subtotal);

        $this->assertEquals(2.95, $charge->toFloat());
    }
}
