<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateShippingAddressesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('shipping_addresses', function (Blueprint $table) {
            $table->uuid('id')->unique()->primary();

            $table->text('first_name')->nullable();
            $table->text('last_name')->nullable();
            $table->text('email')->nullable();
            $table->text('phone')->nullable();
            $table->text('address')->nullable();
            $table->text('address2')->nullable();
            $table->text('apt')->nullable();
            $table->text('city')->nullable();
            $table->text('state')->nullable();
            $table->text('zip')->nullable();
            $table->text('country')->nullable();

            $table->uuid('lead_uuid')->nullable();
            $table->uuid('order_uuid')->nullable();

            $table->uuid('shop_uuid');
            $table->uuid('merchant_uuid');
            $table->uuid('client_uuid');

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
        Schema::dropIfExists('shipping_addresses');
    }
}
