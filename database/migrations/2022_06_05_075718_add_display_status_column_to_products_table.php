<?php

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
        Schema::table('products', function (Blueprint $table) {
            $table->enum('display_status', ['available', 'not available', 'preorder only', 'available_on', 'hide'])->default('hide')->after('stock');
            $table->timestamp('available_on')->nullable(true)->after('display_status');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn('display_status');
            $table->dropColumn('available_on');
        });
    }
};
