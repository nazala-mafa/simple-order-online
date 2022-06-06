<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;
    public $timestamps = true;
    protected $guarded = [];

    public function user() {
        return $this->belongsTo(User::class);
    }

    public function images() {
        return $this->hasMany(ProductImages::class, 'product_id', 'id');
    }

    public function categories() {
        return $this->belongsToMany(ProductCategories::class, 'products_categories_relations', 'product_id', 'category_id');
    }

    public function addStock($stock) {
        $this->stock = (int) $this->stock + $stock;
        $this->save();
        return [ 'success' => true ];
    }

    public function subtractStock($stock) {
        if((int) $this->stock - $stock < 0) return [ 'success' => false ];
        $this->stock = (int) $this->stock - $stock;
        $this->save();
        return [ 'success' => true ];
    }
}
