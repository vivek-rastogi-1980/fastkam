<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'full_name',
        'email',
        'password',
        'role',
        'phone',
        'address',
        'dob',
        'gov_id_type',
        'gov_id_number',
        'api_key',
        'api_secret',
        'wallet_balance',
        'status',
        'plain_password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'plain_password',
        'remember_token',
        'api_secret',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'dob' => 'date',
            'wallet_balance' => 'decimal:2',
        ];
    }

    /**
     * Check if user is an admin.
     */
    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    /**
     * Check if user is a franchise account.
     */
    public function isFranchise(): bool
    {
        return $this->role === 'franchise';
    }

    /**
     * Check if franchise has enough wallet balance.
     */
    public function hasSufficientBalance(float|string $tokens): bool
    {
        return (float) $this->wallet_balance >= (float) $tokens;
    }

    /**
     * Wallet transactions relationship.
     */
    public function walletTransactions(): HasMany
    {
        return $this->hasMany(WalletTransaction::class)->latest();
    }

    /**
     * API execution logs relationship.
     */
    public function apiLogs(): HasMany
    {
        return $this->hasMany(ApiLog::class)->latest();
    }

    /**
     * Custom service prices configured for this franchise.
     */
    public function servicePrices(): HasMany
    {
        return $this->hasMany(FranchiseServicePrice::class, 'user_id');
    }

    /**
     * Get custom service price if set.
     */
    public function getCustomServicePrice(int $serviceId): ?float
    {
        $price = $this->servicePrices()->where('service_id', $serviceId)->value('price');
        return $price !== null ? (float) $price : null;
    }

    /**
     * Ensure default service prices from catalog are populated for this franchise.
     */
    public function ensureDefaultServicePrices(): void
    {
        if (!$this->isFranchise()) {
            return;
        }

        $services = Service::all();
        foreach ($services as $service) {
            FranchiseServicePrice::firstOrCreate(
                [
                    'user_id' => $this->id,
                    'service_id' => $service->id,
                ],
                [
                    'price' => $service->default_franchise_price,
                ]
            );
        }
    }
}
