<?php

declare(strict_types=1);

namespace App\Types;

final readonly class Money implements Floatable
{
    private function __construct(private int $int, private Currency $currency) {}

    public static function zero(?Currency $currency = null): self
    {
        return self::new(0, $currency ?? Currency::default());
    }

    public static function new(float $value, Currency $currency): self
    {
        return new self((int) round($value * 100), $currency);
    }

    public static function mean(self ...$values): self
    {
        if (count($values) === 0) {
            return self::zero(Currency::default());
        }

        return self::zero(Currency::default())
            ->plusAll(...$values)
            ->divideByInt(count($values));
    }

    public function minus(self $other): self
    {
        return $this->plus($other->negate());
    }

    public function plus(self $other): self
    {
        if ($this->currency->equals($other->currency)) {
            return new self($this->int + $other->int, $this->currency);
        }

        return $this->plus($other->convert($this->currency()));
    }

    /**
     * TODO: real conversion
     */
    public function convert(Currency $currency): self
    {
        info('conversion from '.$this->currency()->code().' to '.$currency->code());

        return new self($this->int, $currency);
    }

    public function currency(): Currency
    {
        return $this->currency;
    }

    public function negate(): self
    {
        return new self(-$this->int, $this->currency);
    }

    public function multiply(float|int $factor): self
    {
        return new self((int) round($this->int * $factor), $this->currency);
    }

    public function plusAll(self ...$values): self
    {
        $sum = $this;

        foreach ($values as $value) {
            $sum = $sum->plus($value);
        }

        return $sum;
    }

    public function abs(): self
    {
        return new self(abs($this->int), $this->currency);
    }

    public function divide(Money $other): float
    {
        return $this->int / $other->int;
    }

    public function float(): float
    {
        return round($this->int / 100, precision: 2);
    }

    public function isZero(): bool
    {
        return $this->int === 0;
    }

    public function isPositive(): bool
    {
        return $this->int > 0;
    }

    public function isNegative(): bool
    {
        return $this->int < 0;
    }

    /**
     * TODO: conversion
     */
    public function equals(Money $other): bool
    {
        if ($this->currency->equals($other->currency)) {
            return $this->int === $other->int;
        }

        return $this->equals($other->convert($this->currency()));
    }

    private function divideByInt(int $divisor): self
    {
        return new self((int) ($this->int / $divisor), $this->currency);
    }

    public function toArray(): array
    {
        return [
            'value' => $this->float(),
            'currency' => $this->currency()->toArray(),
        ];
    }

    public function dd(): never
    {
        dd($this->int, $this->currency()->code());
    }

    public function dump(): self
    {
        dump($this->int, $this->currency()->code());

        return $this;
    }

    public function __toString()
    {
        $value = round($this->float(), 2);

        return "$value $this->currency";
    }
}
