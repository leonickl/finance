<?php

declare(strict_types=1);

namespace App\Statistics\Charts;

use App\Dto\Rgb;
use App\Models\Transaction;
use App\Types\DebitCredit;
use App\Types\TransactionCollection;
use Illuminate\Support\Collection;

final readonly class Grouper
{
    public function __construct(private TransactionCollection $list, private DebitCredit $side) {}

    public function grouped(): Collection
    {
        return once(fn () => $this->list
            ->mapToGroups(fn (Transaction $t) => [$t->{$this->side->databaseColumn()} => $t])
            ->map(fn (Collection $transactions, int $accountId) => new Grouped($accountId, $transactions->transactions()))
            ->sortByDesc(fn (Grouped $x) => $x->balance()->float()));
    }

    public function all(): array
    {
        return $this->grouped()->toArray();
    }

    public function x(): array
    {
        return $this->grouped()
            ->map(fn (Grouped $obj) => $obj->account()->fullname)
            ->toArray();
    }

    public function y(): array
    {
        return $this->grouped()
            ->map(fn (Grouped $obj) => $obj->balance()->float())
            ->toArray();
    }

    public function colors(Rgb $rgb): array
    {
        $colors = [];

        $total = $this->list->sumValues()->float();

        $accumulated = 0;

        foreach ($this->grouped() as $elem) {
            $accumulated += $elem->balance()->float();

            $b = (int) round((1 - $accumulated / $total) * 255);

            // red and green stay constant, because one diagram
            // should be more red and the other one more green.
            // change the blue value depending on cake piece size

            $colors[] = (new Rgb(r: $rgb->r, g: $rgb->b, b: $b))->toHex();
        }

        return $colors;
    }
}
