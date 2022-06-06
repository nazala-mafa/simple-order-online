<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cart extends Model
{
    use HasFactory;
    public $timestamps = false;

    function seller() {
        return $this->belongsTo(User::class, 'seller_id', 'id');
    }

    function product() {
        return $this->belongsTo(Product::class);
    }

    static function getCarts() {
        $carts = [];
        $_carts = self::where([
            'buyer_id' => auth()->user()->id,
            'is_checkout' => 0
        ])->orderBy('id', 'desc')->get();
        foreach($_carts as $c) {
            $carts[$c->seller_id]['seller'] = $c->seller;
            $carts[$c->seller_id]['data'][] = $c;
        }
        return array_values($carts);
    }
}
