<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateClientEnabledSmsProvidersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('client_enabled_sms_providers', function (Blueprint $table) {
            $table->uuid('id')->unique()->primary();
            $table->uuid('client_id');
            $table->uuid('provider_id');

            $table->mediumText('misc')->nullable();

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
        Schema::dropIfExists('client_enabled_sms_providers');
    }
}
