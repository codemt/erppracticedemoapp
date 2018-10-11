<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDeletedRequisitionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('deleted_requisitions', function (Blueprint $table) {
            $table->increments('id');
            $table->text('po_no');
            $table->integer('company_id');
            $table->integer('supplier_id');
            $table->integer('distributor_id');
            $table->string('currency_status');
            $table->text('delivery_terms');
            $table->string('purchase_approval_status');
            $table->double('total_price');
            $table->double('dollar_total_price');
            $table->text('project_name');
            $table->string('payment_terms');
            $table->date('purchase_approval_date');
            $table->text('company_invoice_to');
            $table->integer('company_shipping_add');
            $table->integer('supplier_billing_add');
            $table->string('dispatch_through');
            $table->text('other_ref');
            $table->integer('created_by');
            $table->integer('updated_by');
            $table->integer('is_mail');
            $table->string('remark');
            $table->integer('account_updated_by');
            $table->integer('owner_updated_by');
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
        Schema::dropIfExists('deleted_requisitions');
    }
}
