<?php

namespace App\Models\Product;

use App\Models\Category\Category;
use App\Models\Negotiation\Negotiation;
use App\Models\Negotiation\NegotiationProduct;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Product extends Model {
    use HasFactory;

    protected $table = 'products';
    protected $fillable = [
        'name',
        'supplier',
        'stock',
        'image_url',
        'price',
        'category'
    ];

    public function category(): BelongsTo {
        return $this->belongsTo(Category::class);
    }

    public function negotiations(): HasMany {
        return $this->hasMany(Negotiation::class);
    }
}
