<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCheckoutFunnelsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('checkout_funnels', function (Blueprint $table) {
            $table->uuid('id')->unique()->primary();
            $table->uuid('shop_id');
            $table->string('funnel_name');
            $table->uuid('shop_install_id')->nullable();
            $table->string('shop_platform')->nullable();

            $table->boolean('default')->default(0);
            $table->boolean('active')->default(1);
            $table->uuid('client_id');
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
        Schema::dropIfExists('checkout_funnels');
    }
}
