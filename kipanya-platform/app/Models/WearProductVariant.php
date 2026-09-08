<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class WearProductVariant extends Model
{
    protected $fillable = ['wear_product_id', 'size', 'color', 'stock', 'sku'];

    protected function casts(): array
    {
        return ['stock' => 'integer'];
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(WearProduct::class, 'wear_product_id');
    }
}
