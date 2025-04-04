<?php

declare(strict_types=1);

namespace AcmeWidget\Basket\Tests\Types;

use PHPUnit\Framework\TestCase;
use AcmeWidget\Basket\Types\Money;

final class MoneyTest extends TestCase
{
    public function testCreateMoney(): void
    {
        $amount = new Money(3295);
        $this->assertEquals(32.95, $amount->toFloat());
    }

    public function testAddMoney(): void
    {
        $m1 = new Money(1000); // $10.00
        $m2 = new Money(995);  // $9.95
        $sum = $m1->add($m2);
        $this->assertEquals(19.95, $sum->toFloat());
    }

    public function testSubtractMoney(): void
    {
        $m1 = new Money(3295); // $32.95
        $m2 = new Money(1648); // $16.48
        $diff = $m1->subtract($m2);
        $this->assertEquals(16.47, $diff->toFloat()); // or 16.48 based on rounding
    }

    public function testZeroMoney(): void
    {
        $zero = new Money(0);
        $this->assertEquals(0.0, $zero->toFloat());
    }
}
