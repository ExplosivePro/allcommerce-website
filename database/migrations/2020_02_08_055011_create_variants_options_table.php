<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVariantsOptionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('variants_options', function (Blueprint $table) {
            $table->uuid('id')->unique()->primary();

            $table->uuid('shop_id'); //AC Linked
            $table->uuid('inventory_id'); //AC Linked

            $table->string('platform_id')->nullable(); //Shopify Linked
            $table->string('platform')->default('allcommerce');
            $table->string('inventory_platform_id')->nullable(); //Shopify variant id

            $table->string('name');
            $table->integer('position')->nullable();
            $table->longText('values');

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
        Schema::dropIfExists('variants_options');
    }
}
