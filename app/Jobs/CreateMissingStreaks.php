<?php

declare(strict_types=1);

namespace App\Jobs;

use App\Models\Streak;

final class CreateMissingStreaks
{
    public function __invoke(): void
    {
        Streak::all()->each(fn (Streak $streak) => $streak->createMissing());
    }
}
