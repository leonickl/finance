<?php

declare(strict_types=1);

namespace App\Types\Date;

use Illuminate\Support\Carbon;

final readonly class Date
{
    public const float DAYS_PER_YEAR = 365.25;

    private function __construct(private Carbon $carbon) {}

    public static function of(Carbon $carbon): Date
    {
        return new Date($carbon);
    }

    public static function now(): Date
    {
        return new Date(Carbon::now());
    }

    /**
     * @param  string  $string  : four digit year dd.mm.yyyy
     */
    public static function fromGermanDate(string $string): self
    {
        return new Date(Carbon::createFromFormat('d.m.Y', $string));
    }

    /**
     * two digit year dd.mm.yy
     */
    public static function fromShortGermanDate(string $string): Date
    {
        return new Date(Carbon::createFromFormat('d.m.y', $string));
    }

    public static function today(): Date
    {
        return new self(Carbon::today());
    }

    public function dottedDate(): string
    {
        return $this->carbon->format('d.m.Y');
    }

    /** @noinspection PhpSameParameterValueInspection */
    private function format(string $formatter): string
    {
        return $this->carbon->format($formatter);
    }

    public function dashedDateTime(): string
    {
        return $this->format('Y-m-d H:i:s');
    }

    public function dashedDate(): string
    {
        return $this->carbon->format('Y-m-d');
    }

    public function carbon(): Carbon
    {
        return $this->carbon;
    }

    public function month(): Month
    {
        return Month::make(year: $this->carbon->year, month: $this->carbon->month);
    }

    public function year(): Year
    {
        return Year::make($this->carbon->year);
    }
}
