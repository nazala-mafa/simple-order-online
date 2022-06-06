<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class HomepageController extends Controller
{
    public function index () {
        return view('guest.homepage.index', [
            'title' => env('APP_NAME')
        ]);
    }

    public function products () {
        $products = [];
        Product::select(['id', 'user_id', 'name', 'created_at'])->orderBy('id', 'desc')->get()->each(function ($row) use (&$products) {
            $data = $row->toArray();
            $data['images'] = $row->images;
            $data['categories'] = $row->categories()->pluck('category')->toArray();
            $products[] = $data;
        });

        return response()->json([
            'products' => $products
        ]);
    }
}
