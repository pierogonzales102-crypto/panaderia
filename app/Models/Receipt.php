<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Receipt extends Model
{
    protected $fillable = ['order_id', 'receipt_number', 'type', 'pdf_path', 'total'];

    protected function casts(): array
    {
        return ['total' => 'decimal:2'];
    }

    public static function generateNumber(): string
    {
        return 'B-'.date('Y').'-'.str_pad(static::count() + 1, 6, '0', STR_PAD_LEFT);
    }

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }
}
