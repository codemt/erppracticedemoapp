<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateNewManageStocksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('new_manage_stocks', function (Blueprint $table) {

            $table->increments('id');
            $table->integer('product_id')->nullable();
            $table->text('name_description')->nullable();
            $table->text('model_no')->nullable();
            $table->integer('total_qty')->nullable();
            $table->decimal('total_physical_qty')->nullable();
            $table->string('blocked_reason')->nullable();
            $table->integer('blocked_by')->nullable();
            $table->decimal('total_blocked_qty')->nullable();
            $table->string('company_id')->nullable();
            $table->integer('supplier_id')->nullable();
            $table->double('weight')->nullable();
            $table->double('current_market_price')->nullable();
            $table->integer('open_po_qty')->nullable();
            $table->integer('open_so_qty')->nullable();
            $table->integer('po_qty')->nullable();
            $table->dateTime('deleted_at')->nullable();
            $table->timestamps();
    
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('new_manage_stocks');
    }
}
