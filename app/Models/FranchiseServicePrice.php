<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class FranchiseServicePrice extends Model
{
    use HasFactory;

    protected $table = 'franchise_service_prices';

    protected $fillable = [
        'user_id',
        'service_id',
        'price',
    ];

    protected function casts(): array
    {
        return [
            'price' => 'decimal:2',
        ];
    }

    /**
     * Franchise user who has this price.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Service associated with this price.
     */
    public function service(): BelongsTo
    {
        return $this->belongsTo(Service::class);
    }
}
