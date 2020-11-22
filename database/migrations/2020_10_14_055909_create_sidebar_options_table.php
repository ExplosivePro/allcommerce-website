<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSidebarOptionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sidebar_options', function (Blueprint $table) {
            $table->uuid('id')->unique()->primary();
            $table->string('name');
            $table->string('route')->nullable();
            $table->string('page_shown')->nullable();
            $table->string('menu_name')->nullable();
            $table->boolean('is_submenu')->default(0);
            $table->string('permitted_role')->nullable();
            $table->string('permitted_abilities')->nullable();
            $table->boolean('active')->default(1);
            $table->integer('order')->nullable();
            $table->string('icon')->nullable();
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
        Schema::dropIfExists('sidebar_options');
    }
}
