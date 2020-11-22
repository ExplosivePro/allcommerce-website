<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateShopAssignedSmsProvidersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('shop_assigned_sms_providers', function (Blueprint $table) {
            $table->uuid('id')->unique()->primary();
            $table->uuid('shop_uuid');
            $table->uuid('client_enabled_uuid');
            $table->uuid('provider_uuid');

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
        Schema::dropIfExists('shop_assigned_sms_providers');
    }
}
