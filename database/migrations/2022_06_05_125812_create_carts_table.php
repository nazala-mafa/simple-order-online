<?php

use App\Models\Product;
use App\Models\User;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('carts', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(User::class, 'seller_id');
            $table->foreignIdFor(User::class, 'buyer_id');
            $table->foreignIdFor(Product::class);
            $table->integer('amount');
            $table->integer('price');
            // $table->integer('tax');
            // $table->integer('discount');
            $table->integer('price_total');
            $table->boolean('is_checkout')->default(false);
            $table->timestamp('created_at')->useCurrent();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('carts');
    }
};
