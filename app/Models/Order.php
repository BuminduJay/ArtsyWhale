<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id','status','amount_cents','currency',
        'stripe_checkout_session_id','stripe_payment_intent_id','payment_status',
        'shipping_address','billing_address',
    ];

    protected $casts = [
        'shipping_address' => 'array',
        'billing_address'  => 'array',
    ];

    public function user()  { return $this->belongsTo(User::class); }
    public function items() { return $this->hasMany(OrderItem::class); }
}
