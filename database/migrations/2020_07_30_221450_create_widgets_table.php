<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateWidgetsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('widgets', function (Blueprint $table) {
            $table->uuid('id')->unique()->primary();
            $table->uuid('client_id');

            $table->string('name');
            $table->string('description')->nullable();
            $table->string('component_name');
            $table->text('allowed_roles')->nullable();
            $table->text('allowed_abilities')->nullable();

            $table->text('page');
            $table->text('location');

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
        Schema::dropIfExists('widgets');
    }
}
