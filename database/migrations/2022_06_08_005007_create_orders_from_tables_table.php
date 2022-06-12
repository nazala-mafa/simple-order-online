<?php

use App\Models\OrderFromTable;
use App\Models\Product;
use App\Models\Table;
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
        Schema::create('orders_from_tables', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Table::class);
            $table->string('on_behalf_of'); //atas nama
            $table->float('sum_total')->unsigned();
            $table->enum('status', ['in_payment', 'pending', 'proccess', 'ready', 'done']);
            $table->timestamps();
        });

        Schema::create('orders_from_tables_details', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(OrderFromTable::class, 'order_id')->references('id')->on('orders_from_tables');
            $table->foreignIdFor(Product::class);
            $table->float('price');
            $table->integer('amount');
            $table->float('total');
            $table->enum('status', ['pending', 'proccess', 'ready']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('orders_from_tables_details');
        Schema::dropIfExists('orders_from_tables');
    }
};
