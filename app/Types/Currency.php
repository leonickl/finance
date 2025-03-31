<?php

declare(strict_types=1);

namespace App\Types;

use Illuminate\Contracts\Support\Htmlable;
use Override;
use RuntimeException;

final readonly class Currency implements Htmlable
{
    private function __construct(private string $code) {}

    public static function new(string $code): self
    {
        if (mb_strlen($code) !== 3) {
            throw new RuntimeException('Illegal currency code given');
        }

        return new self($code);
    }

    public static function default(): self
    {
        return self::new(env('DEFAULT_CURRENCY'));
    }

    #[Override]
    public function toHtml(): string
    {
        return $this->code();
    }

    public function code(): string
    {
        return $this->code;
    }

    public function equals(Currency $other): bool
    {
        return $this->code === $other->code;
    }

    public function toArray(): array
    {
        return [
            'code' => $this->code,
        ];
    }
}
