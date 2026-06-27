<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class PurchaseOrder extends Model
{
    protected $fillable = [
        'po_number', 'supplier_id', 'status', 'total',
        'expected_date', 'received_date', 'created_by', 'notes',
    ];

    protected function casts(): array
    {
        return [
            'total' => 'decimal:2',
            'expected_date' => 'date',
            'received_date' => 'date',
        ];
    }

    public static function generatePoNumber(): string
    {
        return 'OC-'.date('Ymd').'-'.strtoupper(substr(uniqid(), -4));
    }

    public function supplier(): BelongsTo
    {
        return $this->belongsTo(Supplier::class);
    }

    public function items(): HasMany
    {
        return $this->hasMany(PurchaseOrderItem::class);
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
