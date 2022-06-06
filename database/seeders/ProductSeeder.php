<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\ProductCategories;
use App\Models\ProductImages;
use Faker\Factory;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\File;
use Smknstd\FakerPicsumImages\FakerPicsumImagesProvider;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = Factory::create();
        $faker->addProvider(new FakerPicsumImagesProvider($faker));
        $path = public_path('uploads\images\products\2');
        File::isDirectory($path) or File::makeDirectory($path, 0777, true, true);

        // ProductCategories::truncate();
        // ProductCategories::insert([
        //     ['category' => 'hewan'],
        //     ['category' => 'tumbuhan'],
        //     ['category' => 'alam'],
        //     ['category' => 'buatan'],
        //     ['category' => 'kota'],
        //     ['category' => 'desa'],
        // ]);
        $categoriesId = ProductCategories::all()->pluck('id');

        // Product::truncate();
        Product::factory(10)->create();

        $products = Product::all();
        // ProductImages::truncate();
        $products->each(function ($product, $idx) use ($categoriesId, $faker, $path) {

            $product->categories()->attach($categoriesId->random(random_int(1, 3)));

            foreach(['primary', 'secondary', 'secondary', 'secondary'] as $idx2 => $type) {
                $temp = $faker->image($path, 640, 480, 'animals', false, false, '', false);
                $filename = "image-$idx-$idx2.jpg";
                File::move($temp, $path."\\".$filename);
                ProductImages::insert([
                    'product_id' => $product->id,
                    'type' => $type,
                    'filename' => $filename
                ]);
            }
            
        });
    }
}
