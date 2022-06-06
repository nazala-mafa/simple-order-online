<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\Controller;
use App\Models\ProductCategories;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProductCategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('seller.product-category.index', [
            'title' => 'Product Categories',
            'categories' => ProductCategories::where('user_id', auth()->user()->id)->get()
        ]);          
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('seller.product-category.create', ['title' => 'Add New Category']);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([ 'category' => ['required', 'min:4', 'max:255', 'unique:product_categories,category'] ]);
        ProductCategories::insert([
            'category' => $request->category,
            'user_id' => auth()->user()->id
        ]);
        return redirect()->to('/seller/products-categories')->with('message', 'new category created');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\ProductCategories  $products_category
     * @return \Illuminate\Http\Response
     */
    public function edit(ProductCategories $products_category)
    {
        return view('seller.product-category.edit', [
            'title' => $products_category->category . ' category edit',
            'category' => $products_category
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\ProductCategories  $products_category
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, ProductCategories $products_category)
    {
        $request->validate([
            'category' => ['required', 'min:4', 'max:255']
        ]);
        $products_category->category = $request->category;
        $products_category->save();
        return redirect()->to('/seller/products-categories')->with('message', 'category has been updated');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\ProductCategories  $products_category
     * @return \Illuminate\Http\Response
     */
    public function destroy(ProductCategories $products_category)
    {
        if($products_category->user_id !== auth()->user()->id) return redirect()->back()->withErrors(['you are not authorized']);
        DB::table('products_categories_relations')->where('category_id', $products_category->id)->delete();
        $products_category->delete();
        return redirect()->back()->with('message', $products_category->category . ' category has been deleted');
    }
}
