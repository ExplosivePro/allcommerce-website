<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddLoggedInUserToShopifyInstalls extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('shopify_installs', function (Blueprint $table) {
            $table->uuid('logged_in_user')->nullable()->after('installed');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('shopify_installs', function (Blueprint $table) {
            $table->dropColumn('logged_in_user');
        });
    }
}
