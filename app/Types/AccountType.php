<?php

declare(strict_types=1);

namespace App\Types;

use App\Models\Account;
use Illuminate\Support\Collection;

enum AccountType: int
{
    case ROOT = 0;
    case CASH = 1;
    case BANK = 2;
    case CLAIM = 3;
    case CLAIM_INTEREST = 4;
    case INCOME = 5;
    case EXPENSES = 6;
    case COMPENSATION = 7;
    case EQUITY = 8;
    case INVESTMENT = 9;
    case ASSETS = 10;
    case LIABILITIES = 11;
    case FUTURE_INCOME = 14;
    case FUTURE_EXPENSES = 15;

    public static function all()
    {
        return collect(self::cases())
            ->mapWithKeys(fn (self $case) => [$case->value => $case->name]);
    }

    public static function make(?int $value): self
    {
        return self::tryFrom($value ?? 0) ?? self::ROOT;
    }

    public function equals(self $type): bool
    {
        return $this->value === $type->value;
    }

    public function in(self ...$types): bool
    {
        foreach ($types as $type) {
            if ($this->equals($type)) {
                return true;
            }
        }

        return false;
    }

    /**
     * @return Collection<int, Account>
     */
    public function accounts(): Collection
    {
        return Account::where('group_id', $this->value)->get();
    }

    /**
     * @return Collection<int, self>
     */
    public function children(): Collection
    {
        return collect(self::cases())
            ->filter(fn (self $case) => $case->parent() !== null && $case->parent()->equals($this))
            ->values();
    }

    public function parent(): ?self
    {
        return match ($this) {
            self::ROOT => null,
            self::ASSETS, self::LIABILITIES => self::ROOT,
            self::CASH, self::BANK, self::CLAIM, self::CLAIM_INTEREST, self::INVESTMENT, self::FUTURE_INCOME => self::ASSETS,
            self::COMPENSATION, self::EQUITY, self::FUTURE_EXPENSES => self::LIABILITIES,
            self::INCOME, self::EXPENSES => self::EQUITY,
        };
    }

    public function balance(): Money
    {
        return once(function () {
            $accounts = $this->accounts()->map->balance();
            $children = $this->children()->map->balance();

            return Money::zero()
                ->plusAll(...$accounts)
                ->plusAll(...$children);
        });
    }

    public function statement(): array
    {
        return [
            'name' => $this->name,
            'balance' => $this->balance()->toArray(),
            'children' => $this->children()->map->statement(),
            'accounts' => $this->accounts()->map->withBalance(),
        ];
    }

    public function isClaimType(): bool
    {
        return $this === self::CLAIM_INTEREST || $this === self::CLAIM;
    }
}
