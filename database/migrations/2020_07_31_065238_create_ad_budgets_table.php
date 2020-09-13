<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAdBudgetsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ad_budgets', function (Blueprint $table) {
            $table->uuid('id')->unique()->primary();
            $table->uuid('client_id');

            $table->uuid('market_id');
            $table->string('club_id')->nullable();

            $table->float('facebook_ig_budget', 8, 2)->nullable();
            $table->float('google_budget', 8, 2)->nullable();
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
        Schema::dropIfExists('ad_budgets');
    }
}
