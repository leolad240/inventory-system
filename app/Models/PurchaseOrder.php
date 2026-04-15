<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PurchaseOrder extends Model
{
    protected $fillable = [
        'po_number', 'supplier_name', 'supplier_contact', 'supplier_email',
        'status', 'total_amount', 'order_date', 'expected_date', 'received_date',
        'notes', 'user_id',
    ];

    protected $casts = [
        'order_date' => 'date',
        'expected_date' => 'date',
        'received_date' => 'date',
    ];

    public function items()
    {
        return $this->hasMany(PurchaseOrderItem::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public static function generatePoNumber(): string
    {
        $last = static::latest()->first();
        $number = $last ? ((int) substr($last->po_number, 3)) + 1 : 1;
        return 'PO-' . str_pad($number, 5, '0', STR_PAD_LEFT);
    }
}
