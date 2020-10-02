<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMerchantApiTokensTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('merchant_api_tokens', function (Blueprint $table) {
            $table->uuid('id')->unique()->primary();
            $table->uuid('token');
            $table->uuid('client_id');
            $table->string('token_type')->default('client'); //Theres also merchant and shop
            $table->text('scopes')->nullable();

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
        Schema::dropIfExists('merchant_api_tokens');
    }
}
