<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLeadsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('leads', function (Blueprint $table) {
            $table->uuid('id')->unique()->primary();

            $table->string('reference_type');  // (ie, checkout_funnel, product_funnel, custom_cart, etc)
            $table->string('reference_uuid');  // uuid of the reference table record - which should contain the product being purchased

            $table->text('first_name')->nullable();
            $table->text('last_name')->nullable();
            $table->text('email')->nullable();
            $table->text('phone')->nullable();

            $table->uuid('shipping_uuid')->nullable();
            $table->uuid('billing_uuid')->nullable();
            $table->uuid('order_uuid')->nullable();

            $table->uuid('shop_uuid');
            $table->uuid('merchant_uuid');
            $table->uuid('client_uuid');

            $table->text('ip_address')->nullable();
            $table->mediumText('utm')->nullable();
            $table->mediumText('misc')->nullable();

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
        Schema::dropIfExists('leads');
    }
}
