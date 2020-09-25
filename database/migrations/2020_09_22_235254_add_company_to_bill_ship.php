<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddCompanyToBillShip extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('shipping_addresses', function (Blueprint $table) {
            $table->string('company')->nullable()->after('phone');
        });

        Schema::table('billing_addresses', function (Blueprint $table) {
            $table->string('company')->nullable()->after('phone');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('shipping_addresses', function (Blueprint $table) {
            $table->dropColumn('company');
        });

        Schema::table('billing_addresses', function (Blueprint $table) {
            $table->dropColumn('company');
        });
    }
}
