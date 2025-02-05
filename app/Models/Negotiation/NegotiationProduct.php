<?php

namespace App\Models\Negotiation;

use App\Models\Product\Product;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class NegotiationProduct extends Model {
    use HasFactory;

    protected $table = 'negotiation_products';

    protected $fillable = [
        'negotiation',
        'product',
        'quantity',
        'total'
    ];

    public function negotiation(): BelongsTo {
        return $this->belongsTo(Negotiation::class);
    }

    public function product(): BelongsTo {
        return $this->belongsTo(Product::class);
    }
}
