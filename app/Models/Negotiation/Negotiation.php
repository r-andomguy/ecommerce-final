<?php

namespace App\Models\Negotiation;

use App\Enum\Negotiation\NegotiationStatus;
use App\Models\Party\Party;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Negotiation extends Model {
    use HasFactory;

    protected $table = 'negotiations';
    protected $fillable = [
        'status',
        'customer',
        'supplier',
        'discount',
        'payment_method',
        'shipping_address',
        'total'
    ];

    public function customer(): BelongsTo {
        return $this->belongsTo(Party::class, 'customer');
    }

    public function supplier(): BelongsTo {
        return $this->belongsTo(Party::class, 'supplier');
    }

    public function negotiations(): BelongsToMany {
        return $this->belongsToMany(NegotiationProduct::class, 'negotiation_products');
    }
}
