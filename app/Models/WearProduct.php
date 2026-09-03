<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class WearProduct extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 'slug', 'description', 'category', 'price', 'compare_at_price',
        'image_path', 'badge', 'is_featured', 'is_active', 'sort_order',
    ];

    protected function casts(): array
    {
        return [
            'price' => 'decimal:2',
            'compare_at_price' => 'decimal:2',
            'is_featured' => 'boolean',
            'is_active' => 'boolean',
        ];
    }

    public function variants(): HasMany
    {
        return $this->hasMany(WearProductVariant::class);
    }

    public function getImageUrlAttribute(): string
    {
        return $this->image_path
            ? asset($this->image_path)
            : asset('assets/wear/shirts/black-clean.png');
    }
}
