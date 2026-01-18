<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_number',
        'user_id',
        'customer_name',
        'customer_email',
        'customer_phone',
        'shipping_address',
        'notes',
        'subtotal',
        'shipping_fee',
        'discount',
        'total',
        'status',
        'payment_method',
        'payment_status',
    ];

    protected $casts = [
        'subtotal' => 'decimal:2',
        'shipping_fee' => 'decimal:2',
        'discount' => 'decimal:2',
        'total' => 'decimal:2',
    ];

    const STATUS_PENDING = 'pending';
    const STATUS_CONFIRMED = 'confirmed';
    const STATUS_PROCESSING = 'processing';
    const STATUS_SHIPPED = 'shipped';
    const STATUS_DELIVERED = 'delivered';
    const STATUS_CANCELLED = 'cancelled';

    const PAYMENT_COD = 'cod';
    const PAYMENT_BANK_TRANSFER = 'bank_transfer';
    const PAYMENT_ONLINE = 'online';

    const PAYMENT_STATUS_PENDING = 'pending';
    const PAYMENT_STATUS_PAID = 'paid';
    const PAYMENT_STATUS_FAILED = 'failed';
    const PAYMENT_STATUS_REFUNDED = 'refunded';

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function getNameAttribute()
    {
        return $this->customer_name;
    }

    public function getPhoneAttribute()
    {
        return $this->customer_phone;
    }

    public function getEmailAttribute()
    {
        return $this->customer_email;
    }

    public function getAddressAttribute()
    {
        return $this->shipping_address;
    }

    public function getNoteAttribute()
    {
        return $this->notes;
    }

    public function getPaymentMethodLabelAttribute()
    {
        $methods = [
            'cod' => 'Thanh toán khi nhận hàng (COD)',
            'bank_transfer' => 'Chuyển khoản ngân hàng',
            'online' => 'Thanh toán online',
        ];
        return $methods[$this->payment_method] ?? $this->payment_method;
    }

    public function getStatusLabelAttribute()
    {
        $labels = [
            'pending' => 'Chờ xử lý',
            'confirmed' => 'Đã xác nhận',
            'processing' => 'Đang xử lý',
            'shipping' => 'Đang giao',
            'delivered' => 'Đã giao',
            'cancelled' => 'Đã hủy',
        ];
        return $labels[$this->status] ?? $this->status;
    }

    public static function generateOrderNumber()
    {
        return 'ORD-' . date('YmdHis') . '-' . rand(1000, 9999);
    }

    public static function getStatuses()
    {
        return [
            self::STATUS_PENDING => 'Chờ xử lý',
            self::STATUS_CONFIRMED => 'Đã xác nhận',
            self::STATUS_PROCESSING => 'Đang xử lý',
            self::STATUS_SHIPPED => 'Đang giao hàng',
            self::STATUS_DELIVERED => 'Đã giao hàng',
            self::STATUS_CANCELLED => 'Đã hủy',
        ];
    }

    public static function getPaymentMethods()
    {
        return [
            self::PAYMENT_COD => 'Thanh toán khi nhận hàng',
            self::PAYMENT_BANK_TRANSFER => 'Chuyển khoản ngân hàng',
            self::PAYMENT_ONLINE => 'Thanh toán online',
        ];
    }

    public static function getPaymentStatuses()
    {
        return [
            self::PAYMENT_STATUS_PENDING => 'Chờ thanh toán',
            self::PAYMENT_STATUS_PAID => 'Đã thanh toán',
            self::PAYMENT_STATUS_FAILED => 'Thanh toán thất bại',
            self::PAYMENT_STATUS_REFUNDED => 'Đã hoàn tiền',
        ];
    }

    public function calculateTotal(): float
    {
        $subtotal = $this->items->sum('total');
        $shippingFee = $this->shipping_fee ?? 0;
        $discount = $this->discount ?? 0;
        
        $total = (float)$subtotal + (float)$shippingFee - (float)$discount;
        
        return $total;
    }
}

