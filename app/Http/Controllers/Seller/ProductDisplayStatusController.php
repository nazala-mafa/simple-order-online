<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductDisplayStatusController extends Controller
{
    public function __invoke(Request $request) {
        $product = Product::where('id', $request->product_id)->first();
        $product->display_status = $request->display_status;
        $product->save();
    }
}
