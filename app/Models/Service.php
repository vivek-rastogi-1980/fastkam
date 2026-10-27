<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Service extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'service_code',
        'category',
        'description',
        'endpoint_path',
        'http_method',
        'token_cost',
        'super_admin_cost',
        'default_franchise_price',
        'is_active',
        'sample_payload',
        'required_fields',
    ];

    protected function casts(): array
    {
        return [
            'token_cost' => 'decimal:2',
            'super_admin_cost' => 'decimal:2',
            'default_franchise_price' => 'decimal:2',
            'is_active' => 'boolean',
            'sample_payload' => 'array',
            'required_fields' => 'array',
        ];
    }

    public function apiLogs(): HasMany
    {
        return $this->hasMany(ApiLog::class);
    }

    public function walletTransactions(): HasMany
    {
        return $this->hasMany(WalletTransaction::class);
    }

    /**
     * Franchise-specific pricing overrides.
     */
    public function franchisePrices(): HasMany
    {
        return $this->hasMany(FranchiseServicePrice::class, 'service_id');
    }

    /**
     * Standard IDSPay upstream gateway base costs (1 Token = 1 INR)
     */
    public const IDSPAY_DEFAULT_PRICES = [
        'dl_verification_plus_srv2' => 8.00,
        'pan_details_v2' => 2.50,
        'pan_details_v1' => 2.00,
        'aadhar_to_pan' => 5.00,
        'rc_to_mobile_v3' => 3.50,
        'voter_id_verification_srv2' => 2.00,
    ];

    /**
     * Super Admin purchase / base cost accessor (matches IDSPay upstream gateway cost).
     */
    public function getSuperAdminCostAttribute(): float
    {
        if (isset($this->attributes['super_admin_cost']) && $this->attributes['super_admin_cost'] !== null && (float) $this->attributes['super_admin_cost'] > 0) {
            return (float) $this->attributes['super_admin_cost'];
        }

        return (float) (self::IDSPAY_DEFAULT_PRICES[$this->service_code ?? ''] ?? ($this->attributes['token_cost'] ?? 1.00));
    }

    /**
     * Default price for new franchises only accessor.
     */
    public function getDefaultFranchisePriceAttribute(): float
    {
        if (isset($this->attributes['default_franchise_price']) && $this->attributes['default_franchise_price'] !== null) {
            return (float) $this->attributes['default_franchise_price'];
        }

        return (float) ($this->attributes['token_cost'] ?? 1.00);
    }

    /**
     * Determine effective service cost for a given user.
     * If user is a franchise and has a custom price defined, return that price.
     * If user is a franchise without override, return default franchise price (for new franchise only).
     * Otherwise return Super Admin cost.
     */
    public function getCostForUser(?User $user = null): float
    {
        if ($user && $user->isFranchise()) {
            $custom = FranchiseServicePrice::where('user_id', $user->id)
                ->where('service_id', $this->id)
                ->value('price');

            if ($custom !== null) {
                return (float) $custom;
            }

            return (float) $this->default_franchise_price;
        }

        return (float) $this->super_admin_cost;
    }

    /**
     * Accessor for effective cost.
     */
    public function getEffectiveCostAttribute(): float
    {
        if (isset($this->attributes['effective_cost'])) {
            return (float) $this->attributes['effective_cost'];
        }

        return (float) $this->super_admin_cost;
    }
}
