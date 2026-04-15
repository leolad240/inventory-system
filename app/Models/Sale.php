<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Sale extends Model
{
    protected $fillable = ['sale_number', 'customer_name', 'total_amount', 'notes', 'user_id'];

    public function items()
    {
        return $this->hasMany(SaleItem::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public static function generateSaleNumber(): string
    {
        $last = static::latest()->first();
        $number = $last ? ((int) substr($last->sale_number, 5)) + 1 : 1;
        return 'SALE-' . str_pad($number, 5, '0', STR_PAD_LEFT);
    }
}
