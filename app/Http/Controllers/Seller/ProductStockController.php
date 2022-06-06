<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductStockController extends Controller
{
    public function __invoke(Request $request) {
        $product = Product::where('id', $request->product_id)->first();
        if($request->type === 'add') {
            return response()->json($product->addStock($request->stock));
        } else {
            return response()->json($product->subtractStock($request->stock));
        }
    }
}
