<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Order extends Model
{
    protected $fillable = [
        'order_number', 'user_id', 'type', 'status', 'subtotal', 'discount',
        'tax', 'shipping', 'total', 'discount_code', 'payment_method',
        'payment_status', 'delivery_address', 'delivery_date',
        'custom_instructions', 'reference_image', 'customization_details', 'notes',
    ];

    protected function casts(): array
    {
        return [
            'subtotal' => 'decimal:2',
            'discount' => 'decimal:2',
            'tax' => 'decimal:2',
            'shipping' => 'decimal:2',
            'total' => 'decimal:2',
            'delivery_date' => 'datetime',
        ];
    }

    public static function generateOrderNumber(): string
    {
        return 'PC-'.date('Ymd').'-'.strtoupper(substr(uniqid(), -5));
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function items(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    public function payment(): HasOne
    {
        return $this->hasOne(Payment::class);
    }

    public function receipt(): HasOne
    {
        return $this->hasOne(Receipt::class);
    }

    public function productionOrders(): HasMany
    {
        return $this->hasMany(ProductionOrder::class);
    }

    public function statusLabel(): string
    {
        return match ($this->status) {
            'pendiente' => 'Pendiente',
            'confirmado' => 'Confirmado',
            'en_produccion' => 'En producción',
            'listo' => 'Listo',
            'en_camino' => 'En camino',
            'entregado' => 'Entregado',
            'cancelado' => 'Cancelado',
            default => $this->status,
        };
    }

    public function statusColor(): string
    {
        return match ($this->status) {
            'pendiente' => 'bg-yellow-100 text-yellow-800',
            'confirmado' => 'bg-blue-100 text-blue-800',
            'en_produccion' => 'bg-orange-100 text-orange-800',
            'listo' => 'bg-purple-100 text-purple-800',
            'en_camino' => 'bg-indigo-100 text-indigo-800',
            'entregado' => 'bg-green-100 text-green-800',
            'cancelado' => 'bg-red-100 text-red-800',
            default => 'bg-gray-100 text-gray-800',
        };
    }
}
