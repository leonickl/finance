<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property-read int $id
 * @property-read string $uid
 * @property-read string $raw
 * @property-read int $transaction_id
 * @property-read Transaction $transaction
 */
final class CashImport extends Model
{
    protected $fillable = ['uid', 'raw', 'transaction_id'];

    public function transaction(): BelongsTo
    {
        return $this->belongsTo(Transaction::class, 'transaction_id');
    }
}
