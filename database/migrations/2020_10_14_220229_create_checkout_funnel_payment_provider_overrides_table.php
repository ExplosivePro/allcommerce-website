<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCheckoutFunnelPaymentProviderOverridesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('checkout_funnel_payment_provider_overrides', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->uuid('funnel_uuid');
            $table->uuid('client_enabled_uuid');
            $table->uuid('provider_uuid');

            $table->uuid('shop_uuid');
            $table->uuid('merchant_uuid');
            $table->uuid('client_uuid');
            $table->boolean('active')->default(1);
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
        Schema::dropIfExists('checkout_funnel_payment_provider_overrides');
    }
}
