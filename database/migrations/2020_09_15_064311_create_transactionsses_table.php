<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTransactionssesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('transactions', function (Blueprint $table) {
            $table->uuid('id')->unique()->primary();

            $table->uuid('order_uuid');

            $table->decimal('subtotal', 2)->default(0.00);
            $table->decimal('tax', 2)->default(0.00);
            $table->decimal('shipping', 2)->default(0.00);
            $table->decimal('total', 2)->default(0.00);
            $table->decimal('commission_rate', 2)->default(0.00);
            $table->decimal('commission_amount', 2)->default(0.00);

            $table->string('currency')->default('USD');
            $table->string('symbol')->default('$');
            $table->text('platform_transaction_id')->nullable();

            $table->uuid('shop_uuid');
            $table->uuid('merchant_uuid');
            $table->uuid('client_uuid');

            $table->timestamps();
            $table->softDeletes();

            $table->mediumText('misc')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('transactions');
    }
}
