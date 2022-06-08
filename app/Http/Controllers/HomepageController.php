<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Table;

class HomepageController extends Controller
{
    public function index () {
        $tables = Table::all();
        return view('guest.homepage.index', [
            'title' => env('APP_NAME'),
            'tables' => $tables
        ]);
    }

    public function display(Table $table) {
        $products = Product::where('display_status', 'available')->orderBy('name')->get();
        return view('guest.homepage.display', [
            'noHeader' => true,
            'title' => env('APP_NAME'),
            'products' => $products,
            'table' => $table
        ]);
    }

    public function products () {
        $products = [];
        Product::select(['id', 'user_id', 'name', 'price', 'created_at'])->orderBy('id', 'desc')->get()->each(function ($row) use (&$products) {
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
