<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ApiLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'service_id',
        'service_code',
        'request_payload',
        'response_payload',
        'tokens_deducted',
        'status', // SUCCESS, FAILED, TIMEOUT, INSUFFICIENT_FUNDS, ERROR
        'http_status_code',
        'ip_address',
        'execution_time_ms',
        'error_message',
        'created_at',
    ];

    protected function casts(): array
    {
        return [
            'tokens_deducted' => 'decimal:2',
            'request_payload' => 'array',
            'response_payload' => 'array',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function service(): BelongsTo
    {
        return $this->belongsTo(Service::class);
    }
}
