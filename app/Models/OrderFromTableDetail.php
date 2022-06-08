<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderFromTableDetail extends Model
{
    use HasFactory;
    public $table = 'orders_from_tables_details';
    public $timestamps = false;
    protected $guarded = [];

    function order() {
        return $this->belongsTo(orderFromTable::class, 'order_id', 'id');
    }

    function product() {
        return $this->belongsTo(Product::class);
    }
}
