<?php

declare(strict_types=1);

namespace AcmeWidget\Basket\Types;

/**
 * Immutable Money value object storing amounts in cents to avoid float precision issues.
 */
final class Money
{
    /**
     * @var int Amount in cents (e.g. 3295 for $32.95)
     */
    private int $amount;

    /**
     * @var string Currency code (e.g. "USD")
     */
    private string $currency;  // e.g. 'USD'

    /**
     * @param int $amount Amount in cents
     * @param string $currency ISO 4217 currency code
     */
    public function __construct(int $amount, string $currency = 'USD')
    {
        if ($amount < 0) {
            throw new \InvalidArgumentException('Money amount cannot be negative');
        }
        $this->amount = $amount;
        $this->currency = $currency;
    }

    /**
     * Gets the amount in cents.
     * @return int
     */
    public function getAmount(): int
    {
        return $this->amount;
    }

    /**
     * Gets the currency code.
     * @return string
     */
    public function getCurrency(): string
    {
        return $this->currency;
    }

    /**
     * Converts the amount to a float in dollars.
     * @return float
     */
    public function toFloat(): float
    {
        return $this->amount / 100;
    }

    /**
     * Returns a formatted string like "$32.95".
     * @return string
     */
    public function formatted(): string
    {
        return sprintf('$%.2f', $this->toFloat());
    }

    /**
     * Adds another Money amount and creates a new Money object with amounts added
     * @param Money $other
     * @return Money
     */
    public function add(Money $other): Money
    {
        $this->assertSameCurrency($other);
        return new Money($this->amount + $other->amount, $this->currency);
    }

    /**
     * Creates a new Money by subtracting amounts
     * @param Money $other
     * @return Money
     */
    public function subtract(Money $other): Money
    {
        $this->assertSameCurrency($other);
        $result = $this->amount - $other->amount;
        if ($result < 0) {
            throw new \InvalidArgumentException('Subtraction cannot result in negative Money');
        }
        return new Money($result, $this->currency);
    }

    /**
     * Multiply by factor, e.g. 0.5 for half price, rounding to nearest cent
     * @param float $factor
     * @return Money
     */
    public function multiply(float $factor): Money
    {
        $newAmount = (int) round($this->amount * $factor);
        return new Money($newAmount, $this->currency);
    }

    /**
     * Ensures Money operations (i.e. addition or subtraction) are done to the same currency.
     * @param Money $other
     * @return void
     */
    private function assertSameCurrency(Money $other): void
    {
        if ($this->currency !== $other->currency) {
            throw new \InvalidArgumentException('Cannot operate on different currencies');
        }
    }
}
