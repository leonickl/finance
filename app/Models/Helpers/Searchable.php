<?php

namespace App\Models\Helpers;

use Illuminate\Database\Eloquent\Builder;

trait Searchable
{
    public static function wrap() {}

    public static function search(?string $query): Builder
    {
        $builder = self::query();

        if (! $query) {
            return $builder;
        }

        foreach (self::searchable() as $field) {
            $builder = $builder->orWhereRaw("LOWER($field) LIKE LOWER(?)", ["%{$query}%"]);
        }

        return $builder;
    }

    abstract protected static function searchable(): array;
}
