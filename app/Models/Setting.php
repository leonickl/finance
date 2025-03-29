<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * @property int $id
 * @property string $key
 * @property string|null $value
 */
final class Setting extends Model
{
    use HasFactory;

    public static function findByName(string $name, ?string $defaultValue = null): self
    {
        return self::firstOrCreate([
            'key' => $name,
        ], [
            'value' => $defaultValue,
        ]);
    }

    public function get(): ?string
    {
        return $this->value;
    }

    public function set(mixed $value): self
    {
        $this->update([
            'value' => $value,
        ]);

        return $this;
    }
}
