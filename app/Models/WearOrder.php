<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class WearOrder extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_number', 'user_id', 'customer_name', 'customer_phone', 'customer_email',
        'delivery_address', 'delivery_city', 'notes', 'subtotal', 'delivery_fee', 'total',
        'status', 'payment_status', 'payment_method', 'placed_at',
    ];

    protected function casts(): array
    {
        return [
            'subtotal' => 'decimal:2',
            'delivery_fee' => 'decimal:2',
            'total' => 'decimal:2',
            'placed_at' => 'datetime',
        ];
    }

    public function items(): HasMany
    {
        return $this->hasMany(WearOrderItem::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
