<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLeadAttributesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('lead_attributes', function (Blueprint $table) {
            $table->uuid('id')->unique()->primary();
            $table->uuid('lead_uuid');

            $table->string('name');
            $table->string('value');

            $table->mediumText('misc')->nullable();

            $table->boolean('active')->default(1);

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
        Schema::dropIfExists('lead_attributes');
    }
}
