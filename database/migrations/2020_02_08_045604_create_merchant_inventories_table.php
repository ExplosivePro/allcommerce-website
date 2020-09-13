<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMerchantInventoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('merchant_inventories', function (Blueprint $table) {
            $table->uuid('id')->unique()->primary();
            $table->uuid('shop_id');
            $table->uuid('shop_install_id')->nullable();
            $table->string('platform_id')->nullable();
            $table->string('platform')->default('allcommerce');
            $table->string('title');
            $table->longText('body_html')->nullable();
            $table->string('vendor')->nullable();
            $table->string('product_type')->nullable();
            $table->string('handle')->nullable();
            $table->string('published_at')->nullable();
            $table->text('tags')->nullable();
            $table->boolean('default_item')->default(0);
            $table->boolean('active')->default(0);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('merchant_inventories');
    }
}
