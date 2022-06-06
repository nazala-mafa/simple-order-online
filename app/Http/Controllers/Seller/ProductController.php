<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Http\Requests\Seller\StoreProductRequest;
use App\Http\Requests\Seller\UpdateProductRequest;
use App\Models\ProductCategories;
use App\Models\ProductImages;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Intervention\Image\Facades\Image;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (request('datatables')) return $this->datatables();
        return view('seller.product.index', ['title' => 'List All Products']);
    }
    private function datatables()
    {
        $products = [];
        Product::select(['id', 'user_id', 'name', 'stock', 'display_status', 'price'])->where('user_id', auth()->user()->id)->orderBy('id', 'desc')->get()->each(function ($row) use (&$products) {
            $data = $row->toArray();
            $data['images'] = $row->images()->where('type', 'primary')->first()->filename;
            $data['categories'] = $row->categories()->pluck('category')->toArray();
            $products[] = $data;
        });

        return response()->json([
            'data' => $products
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('seller.product.create', [
            'title' => 'Add Product',
            'categories' => ProductCategories::select('id', 'category as text')->get()
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreProductRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreProductRequest $request)
    {
        /**
         * insert products
         */
        $newProductId = Product::insertGetId([
            'user_id' => auth()->user()->id,
            'name' => $request->name,
            'description' => $request->description ?? '',
            'stock' => 0,
            'price' => $request->price,
        ]);

        /**
         * insert images & handle upload
         */
        $primaryImageFilename = $this->handleUploadedImage($request->file('image_primary'));
        ProductImages::insert([
            'product_id' => $newProductId,
            'type' => 'primary',
            'filename' => $primaryImageFilename
        ]); //primary image
        $secondaryImageDatas = [];
        foreach ($request->file('images') ?? [] as $image) {
            $secondaryImageDatas[] = [
                'product_id' => $newProductId,
                'type' => 'secondary',
                'filename' => $this->handleUploadedImage($image)
            ];
        }
        if (count($secondaryImageDatas)) ProductImages::insert($secondaryImageDatas); //secondary images

        /**
         * insert categories
         */
        if ($request->categories) {
            $categoriesData = array_map(function ($id) {
                return [
                    'category_id' => $id,
                    'product_id' => 4
                ];
            }, $request->categories);
            DB::table('products_categories_relations')->insert($categoriesData);
        }

        return redirect()->to('/seller/products')->with('message', 'Product ' . $request->name . ' Created');
    }

    private function handleUploadedImage($uploadedFile)
    {
        $destinationPath = public_path('/uploads/images/products/' . auth()->user()->id . '/');
        $newFilename = uniqid() . '.' . $uploadedFile->getClientOriginalExtension();

        File::isDirectory($destinationPath) or File::makeDirectory($destinationPath, 0777, true, true);
        $img = Image::make($uploadedFile->getRealPath());
        $img->resize(640, 480, function ($const) {
            $const->aspectRatio();
        })->save($destinationPath . '/' . $newFilename);
        return $newFilename;
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function show(Product $product)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function edit(Product $product)
    {
        return view('seller.product.edit', [
            'title' => $product->name . ' product\' edit',
            'product' => $product,
            'categories' => ProductCategories::select('id', 'category as text')->get()
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateProductRequest  $request
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateProductRequest $request, Product $product)
    {
        /**
         * update image
         */
        if($request->file('image_primary')) {
            $db = $product->images()->where('type', 'primary')->first();
            $filepath = public_path('uploads/images/products/' . auth()->user()->id . '/' . $db->filename);
            if (file_exists($filepath)) unlink($filepath); //delete img file
            $db->filename = $this->handleUploadedImage($request->file('image_primary')); //handle upload file
            $db->save();
        }
        if($request->file('images')) {
            foreach($request->file('images') as $id => $image) {
                $db = $product->images()->where('id', $id)->first();
                if ($db) {
                    $filepath = public_path('uploads/images/products/' . auth()->user()->id . '/' . $db->filename);
                    if (file_exists($filepath)) unlink($filepath); //delete img file
                    $db->filename = $this->handleUploadedImage($image); //handle upload file
                    $db->save();
                } else {
                    $product->images()->insert([
                        'product_id' => $product->id,
                        'type' => 'secondary',
                        'filename' => $this->handleUploadedImage($image)
                    ]);
                }
            }
        }

        /**
         * update product
         */
        $product->name = $request->name;
        $product->description = $request->description;
        $product->price = $request->price;
        $product->save();

        /**
         * update categories
         */
        DB::table('products_categories_relations')->where('product_id', $product->id)->delete();
        DB::table('products_categories_relations')->insert(array_map(function($row)use($product){
            return ['category_id' => $row, 'product_id' => $product->id];
        }, $request->categories));

        return redirect()->back()->with('message', $product->name . ' has been updated');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function destroy(Product $product)
    {
        $message = $product->name . ' product has been deleted'; //message

        DB::table('products_categories_relations')->where('product_id', $product->id)->delete(); //delete categories
        foreach ($product->images->pluck('filename') as $filename) {
            $filepath = public_path('uploads/images/products/' . auth()->user()->id . '/' . $filename);
            if (file_exists($filepath)) unlink($filepath);
        }
        ProductImages::where('product_id', $product->id)->delete(); //delete images
        $product->delete(); //delete product

        return redirect()->back()->with('message', $message);
    }

    public function destroyImage($imageId)
    {
        $productUserId = DB::table('product_images as pi')
            ->join('products as p', 'p.id', '=', 'pi.product_id')
            ->select('p.user_id')
            ->where('pi.id', $imageId)->first()->user_id;
        if ($productUserId !== auth()->user()->id) return redirect()->back()->withErrors(['image' => 'you\'re not authorized']); //authorized

        $productImage = ProductImages::find($imageId);
        $filepath = public_path('uploads/images/products/' . auth()->user()->id . '/' . $productImage->filename);
        if (file_exists($filepath)) unlink($filepath); //delete img file
        $productImage->delete(); //delete img data

        return redirect()->back()->with('message', 'hes been deleted');
    }
}
