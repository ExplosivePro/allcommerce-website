<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class RemoveUuidsFromPhones extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('phones', function (Blueprint $table) {
            $table->dropColumn('shop_uuid', 'merchant_uuid', 'client_uuid');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('phones', function (Blueprint $table) {
            $table->uuid('client_uuid')->after('phone');
            $table->uuid('merchant_uuid')->after('phone');
            $table->uuid('shop_uuid')->after('phone');
        });
    }
}
