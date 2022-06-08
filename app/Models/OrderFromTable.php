<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderFromTable extends Model
{
    use HasFactory;
    public $table = 'orders_from_tables';
    protected $guarded = [];

    // related table
    function _table() {
        return $this->belongsTo(Table::class, 'table_id', 'id');
    }

    function detail() {
        return $this->hasMany(OrderFromTableDetail::class, 'order_id', 'id');
    }
}
