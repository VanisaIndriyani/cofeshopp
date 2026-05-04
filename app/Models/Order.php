<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;

class Order extends Model
{
    public const STATUS_PENDING = 'pending';
    public const STATUS_PROCESSING = 'processing';
    public const STATUS_BREWING = 'brewing';
    public const STATUS_DELIVERING = 'delivering';
    public const STATUS_COMPLETED = 'completed';
    public const STATUS_CANCELLED = 'cancelled';

    protected $fillable = [
        'invoice',
        'table_id',
        'created_by_user_id',
        'customer_name',
        'customer_note',
        'status',
        'subtotal',
        'tax_percent',
        'tax_amount',
        'service_percent',
        'service_amount',
        'grand_total',
        'ordered_at',
        'confirmed_at',
        'completed_at',
    ];

    protected $casts = [
        'subtotal' => 'integer',
        'tax_percent' => 'decimal:2',
        'tax_amount' => 'integer',
        'service_percent' => 'decimal:2',
        'service_amount' => 'integer',
        'grand_total' => 'integer',
        'ordered_at' => 'datetime',
        'confirmed_at' => 'datetime',
        'completed_at' => 'datetime',
    ];

    protected static function booted(): void
    {
        static::creating(function (Order $order) {
            if (! $order->invoice) {
                $order->invoice = self::generateInvoice();
            }

            if (! $order->ordered_at) {
                $order->ordered_at = Carbon::now();
            }

            if ($order->tax_percent === null) {
                $order->tax_percent = (string) Setting::number('tax_percent', 0);
            }

            if ($order->service_percent === null) {
                $order->service_percent = (string) Setting::number('service_percent', 0);
            }
        });
    }

    public static function generateInvoice(): string
    {
        $date = Carbon::now()->format('Ymd');
        $rand = strtoupper(Str::random(4));

        return "CS-{$date}-{$rand}";
    }

    public function table(): BelongsTo
    {
        return $this->belongsTo(Table::class);
    }

    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by_user_id');
    }

    public function items(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    public function payment(): HasOne
    {
        return $this->hasOne(Payment::class);
    }

    public function getStatusLabelAttribute(): string
    {
        return match ($this->status) {
            self::STATUS_PENDING => 'Menunggu Konfirmasi',
            self::STATUS_PROCESSING => 'Diproses',
            self::STATUS_BREWING => 'Sedang Dibuat',
            self::STATUS_DELIVERING => 'Siap Diantar',
            self::STATUS_COMPLETED => 'Selesai',
            self::STATUS_CANCELLED => 'Dibatalkan',
            default => $this->status,
        };
    }
}
