<?php
    use \App\Http\Controllers\Admin\PurchaseRequisitionApprovalController;
?>
@extends('admin.layout.layout')
<meta name="_token" content="{{csrf_token()}}" />
@section('start_form')
    <?=Form::model($update_purchase_requisition_approval_data,['method' => 'PATCH', 'route'=>['purchase-requisition-approval.update',$id], 'class' => 'm-0 form-horizontal'])?>
@stop
@section('top_fixed_content')
<nav class="navbar navbar-static-top">
    <div class="title">
         <h4></h4>
    </div>
    <div class="pl-10">
        <button type="button"class="btn btn-primary btn-sm disabled-btn" title="Generate PO & View" onclick="generatePoModel()">Generate PO & View </button>
        <button type="submit" name="approve" value="approve" class="btn btn-primary btn-sm disabled-btn" title="Generate PO & Send">Generate PO & Send </button>
        <button type="submit" name="reorder"  id="reorder" value="{{ $id }}" class="btn btn-primary btn-sm disabled-btn" title="Re Order PO & Send">Re Order </button>
        <!-- <button type="submit" name="approve" value="approve_mail" class="btn btn-primary btn-sm disabled-btn" title="Approve & Send Email">Approve & Email</button> -->
        @if($update_purchase_requisition_approval_data['purchase_approval_status'] != 'onhold')
            <button type="submit" name="approve" value="onhold" class="btn btn-primary btn-sm disabled-btn" title="Hold On Order">On Hold</button>
        @endif
        <button type="submit" name="approve" value="cancel" class="btn btn-primary btn-sm disabled-btn" title="Back to users Page">Cancel</button>
        @if(App\Helpers\DesignationPermissionCheck::isPermitted('approval.delete'))
        {{-- <input type="hidden" name="id" value="{{ $id }}" id="hidden_id"> --}}
        <button type="submit" name="pending" id="delete" value="{{ $id }} "class="btn btn-primary btn-sm disabled-btn" title="Hold On Order">Delete</button>
        @endif
    </div>
</nav>
{{-- <form action="{{ route('approval.delete') }}" method="post"> 
        <input type="hidden" name="id" value="{{ $id }}" id="hidden_id">
        <button type="submit" name="approve" value="delete" class="btn btn-primary btn-sm disabled-btn" title="Back to users Page">Delete</button>
</form> --}}
@stop
@section('content')
<div class="row">
    <div class="col-md-12 mb-30">
        <div class="card">
            <div class="card-title-w-btn">
                    <h4 class="title">Purchase Requisition Approval</h4>
            </div><hr>
            <div class="row">
                <div class="col-md-8">
                    <div class="row">
                        <div class="col-md-12">
                            <input type="hidden" name="id" value="<?= $id?>" id="hidden_id">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group @if($errors->has('company_id')) has-error @endif">
                                        <div class="col-md-12">Company Name</div>
                                        <div class="col-md-12">
                                            <?= Form::text('company_id',$company_name['company_name'],array('class' => 'form-control select_2 select company','placeholder'=>'Company Name','id'=>'company_name')) ?>
                                            <span id="select_2_error" class="help-inline text-danger"><?=$errors->first('company_id')?></span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group @if($errors->has('supplier_id')) has-error @endif">
                                        <div class="col-md-12">Manufacturer Name</div>
                                        <div class="col-md-12">
                                            <?= Form::text('supplier_id',$supplier_name['supplier_name'],array('class' => 'form-control select_2 select supplier','placeholder'=>'Manufacturer Name','id' => 'supplier')) ?>
                                            <span id="select_2_error" class="help-inline text-danger"><?=$errors->first('supplier_id')?></span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group @if($errors->has('delivery_terms')) has-error @endif">
                                        <div class="col-md-12">Delivery Terms</div>
                                        <div class="col-md-12">
                                            <?= Form::text('delivery_terms',old('delivery_terms'),array('class' => 'form-control delivery_terms','placeholder'=>'Delivey Terms','id'=>"delivery_terms")) ?>
                                            <span id="select_2_error" class="help-inline text-danger"><?=$errors->first('delivery_terms')?></span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group @if($errors->has('project_name')) has-error @endif">
                                        <div class="col-md-12">Project name<sup class="text-danger">*</sup></div>
                                        <div class="col-md-12">
                                            <?= Form::text('project_name',old('project_name'),array('class' => 'form-control project_name','placeholder'=>'Project Name')) ?>
                                            <span id="select_2_error" class="help-inline text-danger"><?=$errors->first('project_name')?></span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group @if($errors->has('currency_status')) {{ 'has-error' }} @endif">
                                        <div class="col-md-12">Rupee/Dollar</div>
                                        <div class="col-md-12">
                                            <div class="animated-radio-button pull-left mr-10">
                                                <label class="control-label" for="is_active_true">
                                                    <input type="radio" id="currency_status" name="currency_status" value="rupee" {{old('currency_status',$update_purchase_requisition_approval_data['currency_status']) == 'rupee' ? 'checked' : 'disabled'}} id="is_active_true" checked="checked" class="cur_status">
                                                    <span class="label-text"></span> Rupee
                                                </label>
                                            </div>
                                            <div class="animated-radio-button pull-left">
                                                <label class="control-label" for="is_active_false">
                                                    <input type="radio" name="currency_status" value="dollar" {{old('currency_status',$update_purchase_requisition_approval_data['currency_status']) == 'dollar' ? 'checked' : 'disabled'}} id="is_active_false" class="cur_status">
                                                    <span class="label-text"></span> Dollar
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group @if($errors->has('payment_terms')) has-error @endif">
                                        <div class="col-md-12">Payment Terms<sup class="text-danger">*</sup></div>
                                        <div class="col-md-12">
                                            <?= Form::text('payment_terms',old('payment_terms'),array('class' => 'form-control payment_terms','placeholder'=>'Payment Terms')) ?>
                                            <div id="payment_suggetion"></div>
                                            <span id="select_2_error" class="help-inline text-danger"><?=$errors->first('payment_terms')?></span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group @if($errors->has('distributor_id')) has-error @endif">
                        <div class="col-md-12">Distributor Name</div>
                        <div class="col-md-12">
                            <?= Form::text('distributor_id',$distributor_name['distributor_name'],array('class' => 'form-control select_2 select distributor','placeholder'=>'Distributor Name','id'=>'distributor_name')) ?>
                            <span id="select_2_error" class="help-inline text-danger"><?=$errors->first('distributor_id')?></span>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group @if($errors->has('remark')) has-error @endif">
                        <div class="col-md-12">Remark</div>
                        <div class="col-md-12">
                            <?= Form::textarea('remark',old('remark'),array('class' => 'form-control remark','placeholder'=>'Remark','rows'=>'5')) ?>
                            <span id="" class="help-inline text-danger"><?=$errors->first('remark')?></span>
                        </div>
                    </div>
                </div>
                <div class="col-md-12">
                    <div class="form-group row">
                        <div class="col-md-12">
                            <table class="table m-0 v-top vertical-align" style="border: medium none;">
                                <thead>
                                    <tr>
                                        <th style="border:none">Model No</th>
                                        <th style="border:none">Product Name</th>
                                        <th style="border:none">QTY<sup class="text-danger">*</sup></th>
                                        <th style="border:none">Unit Price<sup class="text-danger">*</sup></th>
                                        <th style="border:none">Total Price</th>
                                        <th style="border:none">Last PO</th>
                                        <th style="border:none">Last PO2</th>
                                        <th style="border:none"></th>
                                        <th style="border:none"></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr id="shipping">
                                        <td class="col-md-2" style="border:none;padding-left: 0">
                                            <?= Form::text('model_no',old('model_no'), ['class' => 'form-control select2 model_no','id'=>'model_no','style'=>'width:120px']); ?>
                                            <span id="model_no_error" class="help-inline text-danger"><?= $errors->first('shipping.shipping.model_no') ?></span>
                                        </td>
                                        <td class="col-md-2" style="border:none">
                                            <?= Form::text('product_name',old('product_name'),['class' => 'form-control select2 product_name','id'=>'product_name','style'=>'width:120px','readonly'=>'readonly']); ?>
                                            <span id="product_name_error" class="help-inline text-danger"><?= $errors->first('shipping.shipping.product_name') ?></span>
                                        </td>
                                        <td class="col-md-2" style="border:none">
                                            <?= Form::number('qty','1', ['class' => 'form-control qty qty_only','id'=>'qty','style'=>'width:120px']); ?>
                                            <span id="qty_error" class="help-inline text-danger"><?= $errors->first('shipping.shipping.qty') ?></span>
                                        </td>
                                        <td class="col-md-2" style="border:none">
                                            <?= Form::text('unit_price',old('unit_price'), ['class' => 'form-control select2 unit_price number_only','id'=>'unit_price','style'=>'width:80px']); ?>
                                            <span id="unit_price_error" class="help-inline text-danger"><?= $errors->first('shipping.shipping.unit_price') ?></span>
                                        </td>
                                        <td class="col-md-2" style="border:none">
                                               <?= Form::text('total_price',old('total_price'),['class' => 'form-control select2 total_price','id'=>'total_price','readonly'=>true,'style'=>'width:120px']); ?>
                                            <span id="total_price_error" class="help-inline text-danger"><?= $errors->first('shipping.shipping.total_price') ?></span>
                                        </td>
                                        <td class="col-md-2" style="border:none">
                                               <?= Form::text('last_po',old('last_po'),['class' => 'form-control select2 last_po','id'=>'last_po','readonly'=>true]); ?>
                                            <span id="last_po_error" class="help-inline text-danger"><?= $errors->first('shipping.shipping.last_po') ?></span>
                                        </td>
                                        <td class="col-md-2"  style="border:none;padding-right: 0">
                                            <?= Form::text('last_po2',old('last_po2'), ['class' => 'form-control select2 last_po2','id'=>'last_po2','readonly'=>true,'style'=>'width:80px']); ?>
                                            <span id="last_po2_error" class="help-inline text-danger"><?= $errors->first('shipping.shipping.last_po2') ?></span>
                                        </td>
                                        <td class="col-md-3" style="border:none;padding-left: 0">
                                            <?= Form::hidden('id',old('id'), ['class' => 'form-control id','id'=>'id']); ?>
                                            <span id="id_error" class="help-inline text-danger"><?= $errors->first('shipping.shipping.id') ?></span>
                                        </td>
                                        <td class="col-md-0" style="border:none">
                                            <a id="shipping_remove" class="pt-10 pull-left btn-remove" onclick="ajaxCall()" ></a>
                                            <a id="shipping_add" class="pt-10 pull-left btn-add" onclick="btnAdd()"></a>
                                        </td>
                                    </tr>
                                    <tr>
                                    <td colspan="3" style="border: none;"><strong>Qty And Unit Price required for export and could not be 0.</strong></td>
                                    <td colspan="" align="right" style="border: none;font-weight: bold;font-size: 16px">Total price :</td>
                                    <td style="border: none;font-weight: bold;font-size: 16px">
                                        <input type="text" class="hidden_total_price" value="<?= $total_calculation?> @if($update_purchase_requisition_approval_data['currency_status'] == 'dollar'){{'USD'}}@else{{'INR'}} @endif" style="border:none;width: 120px;" readonly>
                                    </td>
                                    <td colspan="2" align="right" style="border: none;font-weight: bold;font-size: 16px" class="export_button">
                                        <a href="<?= route('purchase.requisition-approval.exportPrItem',$id)?>" type="button" class="btn btn-default btn-sm export_link" title="Export to CSV">Export</button>
                                    </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                @include('admin.layout.overlay')
            </div>
        </div>
        <div class="card">
            <div class="card-title-w-btn">
                    <h4 class="title">Purchase Requisition Approval Other Details</h4>
            </div><hr>
            <div class="row">
                <div class="col-md-12">
                    <div class="row">                
                        <div class="col-md-4">
                            <div class="form-group @if($errors->has('spoc_name')) has-error @endif">
                                <div class="col-md-12">SPOC Name<sup class="text-danger">*</sup></div>
                                <div class="col-md-12">
                                    <?= Form::text('spoc_name', $company_name['spoc_name'],array('class' => 'form-control select_2 spoc_name','placeholder'=>'SPOC Name','maxlength' => 100)) ?>
                                    <span id="select_2_error" class="help-inline text-danger"><?=$errors->first('spoc_name')?></span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group @if($errors->has('spoc_email')) has-error @endif">
                                <div class="col-md-12">SPOC Email<sup class="text-danger">*</sup></div>
                                <div class="col-md-12">
                                    <?= Form::select('spoc_email[]',$email, old('spoc_email[]'),array('class' => 'form-control select2_2 email','id'=>'multi_select_email','multiple'=>true)) ?>
                                    <span id="select_2_error" class="help-inline text-danger"><?=$errors->first('spoc_email')?></span>
                                </div>
                            </div>
                        </div>                    
                        <div class="col-md-4">
                            <div class="form-group @if($errors->has('spoc_phone')) has-error @endif">
                                <div class="col-md-12">SPOC Phone<sup class="text-danger">*</sup></div>
                                <div class="col-md-12">
                                    <?= Form::select('spoc_phone[]',$phoneno, old('spoc_phone[]'),array('class' => 'form-control select2_2 phone','id'=>'multi_select','multiple' => true)) ?>
                                    <span id="select_2_error" class="help-inline text-danger"><?=$errors->first('spoc_phone')?></span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-12">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group @if($errors->has('company_invoice_to')) has-error @endif">
                                <div class="col-md-12">Company Invoice To</div>
                                <div class="col-md-12">
                                    <?= Form::text('company_invoice_to',$company_invoice_add['billing_address'],array('class' => 'form-control company_invoice_to','placeholder'=>'Company Invoice To','readonly'=>'readonly')) ?>
                                    <span id="select_2_error" class="help-inline text-danger"><?=$errors->first('company_invoice_to')?></span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group @if($errors->has('company_shipping_add')) has-error @endif">
                                <div class="col-md-12">Company Shipping Address<sup class="text-danger">*</sup></div>
                                <div class="col-md-12">
                                    <?= Form::select('company_shipping_add',$company_shipping_add_unique,old('company_shipping_add'),array('class' => 'form-control select2 company_shipping_add')) ?>
                                    <span id="select_2_error" class="help-inline text-danger"><?=$errors->first('company_shipping_add')?></span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group @if($errors->has('supplier_billing_add')) has-error @endif">
                                <div class="col-md-12">Supplier Billing Address<sup class="text-danger">*</sup></div>
                                <div class="col-md-12">
                                    <?= Form::select('supplier_billing_add',$supplier_billing_add_unique,old('supplier_billing_add'),array('class' => 'form-control select2 supplier_billing_add')) ?>
                                    <span id="select_2_error" class="help-inline text-danger"><?=$errors->first('supplier_billing_add')?></span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-12">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group @if($errors->has('company_invoice_to')) has-error @endif">
                                <div class="col-md-12">Po No</div>
                                <div class="col-md-12">
                                    <?= Form::text('po_no',null,array('class' => 'form-control po_no','readonly'=>'readonly')) ?>
                                    <span id="select_2_error" class="help-inline text-danger"><?=$errors->first('po_no')?></span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group @if($errors->has('dispatch_through')) has-error @endif">
                                <div class="col-md-12">Dispatch Through<sup class="text-danger">*</sup></div>
                                <div class="col-md-12">
                                    <?= Form::select('dispatch_through',config('Constant.dispatch_value'),old('dispatch_through'),array('class' => 'form-control select2 dispatch_through')) ?>
                                    <span id="" class="help-inline text-danger"><?=$errors->first('dispatch_through')?></span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group @if($errors->has('other_ref')) has-error @endif">
                                <div class="col-md-12">Other Reference</div>
                                <div class="col-md-12">
                                    <?= Form::text('other_ref',old('other_ref'),array('class' => 'form-control other_ref','placeholder'=>'Other Reference')) ?>
                                    <span id="select_2_error" class="help-inline text-danger"><?=$errors->first('other_ref')?></span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="text-right">
        <button type="button"class="btn btn-primary btn-sm disabled-btn" title="Generate PO & View" onclick="generatePoModel()">Generate PO & View </button>
        <button type="submit" name="approve" value="approve"  class="btn btn-primary btn-sm disabled-btn" title="Generate PO & Send">Generate PO & Send </button>
        <button type="submit" name="reorder" value="reorder" class="btn btn-primary btn-sm disabled-btn" title="Re Order PO & Send">Re Order </button>
        <!-- <button type="submit" name="approve" value="approve_mail" class="btn btn-primary btn-sm disabled-btn" title="Approve & Send Email">Approve & Email</button> -->
        @if($update_purchase_requisition_approval_data['purchase_approval_status'] != 'onhold')
            <button type="submit" name="approve" value="onhold" class="btn btn-primary btn-sm disabled-btn" title="Hold On Order">On Hold</button>
        @endif
        <button type="submit" name="approve" value="cancel" class="btn btn-primary btn-sm disabled-btn" title="Back to users Page">Cancel</button>
</div>

<div id="PoModel" class="modal fade bs-example-modal-lg" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" style="text-align: center">Purchase Order</h4>
            </div>
            <div class="modal-body box no-border">
                <div id="modal-po-info"></div>
            </div>
            <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Send</button>
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
          </div>
        </div>
    </div>
</div>
@stop

@section('end_form')
<?=Form::close()?>
@stop
@section('script')
<?= Html::script('backend/js/jquery.form.min.js',[],IS_SECURE) ?>
<?= Html::script('backend/js/dynamicform.js',[],IS_SECURE) ?>
<?= Html::script('backend/js/select2.min.js',[],IS_SECURE) ?>

<script type="text/javascript">
    $('#payment_suggetion').hide();
    if($('.company').val() == "" || $('.supplier').val() == ""){
        $('.model_no').prop('disabled',true);
    }
    $('.company').prop('disabled',true);
    $('.supplier').prop('disabled',true);
    $('.distributor').prop('disabled',true);
    $('.delivery_terms').prop('readonly',true);
    $('.model_no').prop('readonly',true);
    var status = "<?= $update_purchase_requisition_approval_data['purchase_approval_status']?>";
    //if(status == 'pending'){
       // $('.qty').prop('readonly',true);
    //}
    $(document).ready(function () {
        var list = $('tbody tr');
            ajaxCall();

           var comp_name =  $('#company_name').val();
          var manufacturer_name = $('#supplier').val();
          var delivery_terms = $('#delivery_terms').val();
        //   console.log(comp_name,manufacturer_name,delivery_terms);

    $.each(list,function(k,v){
            if($('#total_price'+k).val() == '0.00'){
                $('#unit_price'+k).val('');
            }
        });

        $('#reorder').click(function(e){

                e.preventDefault();
                var reorderId = $('#reorder').val();
                console.log("Re Order ID " +reorderId);
                var company_id = $('#company_name').val();
                var supplier = $('#supplier').val();
                var distributor_name = $('#distributor_name').val();
                var delivery_terms = $('#delivery_terms').val();
                var currency = $('#currency_status').val();
                var model_no = $('#shipping .model_no').val();
                var product_name = $('#shipping .product_name').val();
               
                var qty = $('#shipping .qty').val();
                console.log("Company Name is " +company_id);
                console.log('supplier is ' +supplier);
                console.log('Delivery Terms ' +delivery_terms);
                console.log('currency is ' +currency);
                console.log('Distributor Name is ' +distributor_name);
                console.log(company_id,supplier,delivery_terms,currency,distributor_name);
                console.log("Product Name is  " +product_name);
                console.log("Quantity Ordered is " +qty);
                console.log("Model No. is " +model_no);

                var shipping = {};
                var order = {};
                var model_object = {

                        "model_no": model_no,

                }
                var final = {

                        "model_no":model_no,
                        "product_name":product_name,
                        "qty":qty

                }
                
                shipping = Object.assign({
                        "shipping": final,
                        "save_new":'save_new'

                });

                $.ajaxSetup({
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
                        }
                 });
                $.ajax({
               url: "{{ url('/admin/purchase-requisition/create') }}",
               type: 'post',
               datatype: 'JSON',
                data: {
                        'company_id': company_id,
                        'supplier_id':supplier,
                        'distributor_id':distributor_name,
                        'delivery_terms':delivery_terms,
                        'currency_status':currency,
                        'shipping': shipping
                
                 },
               success: function(result){


                   var final = JSON.stringify(result);
                   console.log(final);
                 toastr.success('Record Given for Re Order');
                window.location.href = '{{ url("/admin/purchase-requisition-approval/index") }}'; 
                //   $('.alert').show();
                //   $('.alert').html(result.success);
               },
               error: function (data) {
                  console.log('Error:', data);
                  window.location.href = '{{ url("/admin/purchase-requisition-approval/index") }}';
                  
                 }
             });
             
             

             


        });

        $('#delete').click(function(e){
            
            e.preventDefault();
            console.log(e);
           var pendingId =  $('#delete').val();
           console.log("Pending ID is" +pendingId);

           $.ajaxSetup({
               headers: {
                   'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
               }
           });

             $.ajax({
               url: "{{ url('/admin/purchase-requisition-approval/delete') }}",
               type: 'post',
               datatype: 'JSON',
                data: {'id': pendingId },
               success: function(result){


                   var final = JSON.stringify(result);
                   console.log(final);
                   if(result == 'Not Allowed'){

                        //   toastr.error('Not Allowed');
                        window.location.href = '{{ route("access.denied") }}'; 


                }
                else
                {

                    toastr.success('Record Given for Deletion');
                    window.location.href = '{{ url("/admin/purchase-requisition-approval/index") }}'; 

                }
                
               },
               error: function (data) {

                  console.log('Error:', data);
                  
                 }
             });



     });




    });



    function btnAdd(){
        $(this).delay(10).queue(function() {

            $(this).hide();
            
            ajaxCall();
            
            $(this).dequeue();
        }); 
    }
    function ajaxCall(){
        select_list = $('tbody tr');
        // console.log(select_list);
        $.each(select_list,function(k,v){
            // $(v).find('#model_no'+k).select2({
            //     placeholder : 'Select model no'
            // });
            // $(v).find('#product_name'+k).select2({
            //     placeholder : 'Select product name'
            // });
            // $(v).find('#qty'+k).select2({
            //     placeholder : 'Select model_no'
            // });


            $(v).find("#model_no"+k).change(function(){
                var model_no = $(this).val();
                // alert($(this).val());
                var id = $("#hidden_id").val();
                var token = "{{csrf_token()}}";
                var url = "{{route('purchase-requisition-approval.edit',':id')}}";
                var path = url.replace(':id',id);
                $.ajax({
                    type : 'GET',
                    url : path,
                    data : {
                        'model_no' : model_no,
                        '_token' : token
                    },
                    success : function(data){
                         console.log(data);
                        $.each(data,function(i,o){
                            // toappend +='<option>'+o+'</option>';
                            $("#product_name"+k).val(data.name_description);
                        });
                    },
                    error : function(data){

                        console.log(data);
                    }
                })
            });
        });
    }
    var customer_shipping_detail =  $("#shipping").dynamicForm("#shipping_add", "#shipping_remove", {
        limit: 10,
        normalizeFullForm : false,
    });

    old_data = <?= json_encode(old('shipping.shipping',$update_purchase_requisition_approval_detail)) ?>;

    customer_shipping_detail.inject(old_data);

    var select_list = $('tbody tr');

    /*if (old_data.length > 0) {
        $.each(select_list,function(k,v){
            var model_no = old_data[k]['model_no'];
            var model_no_test = getmodel_no(model_no);
            $(v).find('.select_product_name').html('').select2({'data':model_no_test}).select2('val',old_data[k]['product_name']);
            $(v).find('.select_qty').select2('val',old_data[k]['qty']);
        });
    };
*/

    @if($errors)
        var detail_Errors = <?= json_encode($errors->toArray()) ?>;
        
        $.each(detail_Errors, function(id,msg){
            var id_arr = id.split('.');
            if (id_arr[3] == 'qty') {
                $('#'+id_arr[3]+id_arr[id_arr.length-2]).closest('td').find('span').text(msg[0]);
            }
            if (id_arr[3] == 'product_name') {
                $('#'+id_arr[3]+id_arr[id_arr.length-2]).closest('td').find('span').text(msg[0]);
            }
            if (id_arr[3] == 'model_no') {
                $('#'+id_arr[3]+id_arr[id_arr.length-2]).closest('td').find('span').text(msg[0]);
            }
            if (id_arr[3] == 'unit_price') {
                    $('#'+id_arr[3]+id_arr[id_arr.length-2]).closest('td').find('span').text(msg[0]);
            }
            if (id_arr[3] == 'last_po') {
                $('#'+id_arr[3]+id_arr[id_arr.length-2]).closest('td').find('span').text(msg[0]);
            }
            if (id_arr[3] == 'last_po2') {
                $('#'+id_arr[3]+id_arr[id_arr.length-2]).closest('td').find('span').text(msg[0]);
            }
        });
    @endif
    $('.company_shipping_add').select2({
        placeholder : "Select Company Address",
    });
    $('.supplier_billing_add').select2({
        placeholder : "Select Supplier Billing Address",
    });
    $('.dispatch_through').select2({
        placeholder : "Select Dispatch Through",
    });
    $("#is_active_false").click(function(){
        $(this).prop('checked','checked');
    });
    $('.payment_terms').keyup(function(){
        // alert($(this).val());
        var payment_terms_value = $(this).val();
        var id = $("#hidden_id").val();
        // console.log(id);
        var token = "{{csrf_token()}}";
        var url = "{{route('purchase-requisition-approval.edit',':id')}}";
        var path = url.replace(':id',id);
        $.ajax({
            type : 'GET',
            url : path,
            data : {
                'payment_terms' : payment_terms_value,
                '_token' : token
            },
            dataType : 'html',
            success : function(data){
                $('#payment_suggetion').show();
                $('#payment_suggetion').html(data);
            },
            error : function(data){

                console.log(data);
            }
        });
    });
    function selectPayment(payment_value){
        // console.log(payment_value);
        $('.payment_terms').val(payment_value);
        $('#payment_suggetion').hide();
    }
    var total_amount = 0;
    // $('.export_button').show();
        var qt = $('tbody tr').parent().find('.qty');
        var u_pr = $('tbody tr').parent().find('.unit_price');
        $(qt).each(function() {
            var qty_v = this.value;
            $(u_pr).each(function(){
                var unit_price = this.value;
                if(qty_v == 0 || qty_v == '' || unit_price == 0.00 || unit_price == '' || unit_price == 0){
                    $('.export_button').css('cursor','not-allowed');
                    $('.export_link').removeAttr('href').css('cursor','not-allowed');
                    $('.export_link').removeAttr('href').css('text-decoration','inherit');
                }
                else{
                    $('.export_button').css('cursor','pointer');
                    $('.export_link').attr('href','<?= route('purchase.requisition-approval.exportPrItem',$id)?>').css('cursor','pointer');
                    $('.export_link').attr('href','<?= route('purchase.requisition-approval.exportPrItem',$id)?>').mouseover(function(){$(this).css('text-decoration','underline')});
                    $('.export_link').attr('href','<?= route('purchase.requisition-approval.exportPrItem',$id)?>').mouseout(function(){$(this).css('text-decoration','inherit')});
                }
            });
        });
    $.each(select_list,function(k,v){

        $(v).find("#qty"+k).keyup(function(key,value){
            var qty_v = new Array();
            var flag = false;
            $(qt).each(function() {
                if(this.value == 0 || this.value == ''){
                    flag = true;
                }else{
                    qty_v.push(this.value);
                }
            });
            if(flag == true){
                $('.export_button').css('cursor','not-allowed');
                $('.export_link').removeAttr('href').css('cursor','not-allowed');
                $('.export_link').removeAttr('href').css('text-decoration','inherit');
            }else{
                $('.export_button').css('cursor','pointer');
                $('.export_link').attr('href','<?= route('purchase.requisition-approval.exportPrItem',$id)?>').css('cursor','pointer');
                $('.export_link').attr('href','<?= route('purchase.requisition-approval.exportPrItem',$id)?>').mouseover(function(){$(this).css('text-decoration','underline')});
                $('.export_link').attr('href','<?= route('purchase.requisition-approval.exportPrItem',$id)?>').mouseout(function(){$(this).css('text-decoration','inherit')});
            }
        });
        $(v).find("#unit_price"+k).keyup(function(key,value){
            // console.log(1);
            var unit_p = new Array();
            var flag_p = false;
            $(u_pr).each(function() {
                console.log(this.value);
                if(this.value == 0.00 || this.value == '' || this.value == 0){
                    flag_p = true;
                }else{
                    unit_p.push(this.value);
                }
            });
            if(flag_p == true){
                $('.export_button').css('cursor','not-allowed');
                $('.export_link').removeAttr('href').css('cursor','not-allowed');
                $('.export_link').removeAttr('href').css('text-decoration','inherit');

            }else{
                $('.export_button').css('cursor','pointer');
                $('.export_link').attr('href','<?= route('purchase.requisition-approval.exportPrItem',$id)?>').css('cursor','pointer');
                $('.export_link').attr('href','<?= route('purchase.requisition-approval.exportPrItem',$id)?>').mouseover(function(){$(this).css('text-decoration','underline')});
                $('.export_link').attr('href','<?= route('purchase.requisition-approval.exportPrItem',$id)?>').mouseout(function(){$(this).css('text-decoration','inherit')});
            }
        });
        function chnage_total_price(){

            var hidden_value = $('.hidden_total_price').val();
            var value = hidden_value.split(' ');
            var previous_total_price = $(v).find("#total_price"+k).val();
            var price_value = $(v).find("#unit_price"+k).val();
            var qty_value = $(v).find("#qty"+k).val();
            var total_price = price_value * qty_value;
            $(v).find('#total_price'+k).val(total_price.toFixed(2));
            total_amount_qty = value[0]-previous_total_price;
            var total = total_price + total_amount_qty;
            if($('.cur_status:checked').val() == 'rupee'){
                $('.hidden_total_price').val(total.toFixed(2)+' INR');
            }
            if($('.cur_status:checked').val() == 'dollar'){
                $('.hidden_total_price').val(total.toFixed(2)+' USD');
            }
            var model_no = $('#model_no'+k).val();
            var product_name = $('#product_name'+k).val();
            var qty = $('#qty'+k).val();
            var unit_price = $('#unit_price'+k).val(); 
            var total_price = $('#total_price'+k).val(); 
            var last_po =$('#last_po'+k).val(); 
            var last_po2 = $('#last_po2'+k).val(); 
            var update_url = "<?= route('purchase.requisition-approval.getItemValue')?>";
            var id = $('#hidden_id').val();
            var cur_status = $('.cur_status:checked').val();
            var total_hidden_price = $('.hidden_total_price').val();
            var token = "{{csrf_token()}}";
            if(model_no != undefined && product_name!= undefined && qty!= undefined && unit_price!= undefined && total_price!= undefined && last_po!= undefined && last_po2!= undefined){
                $.ajax({
                    type : 'GET',
                    url : update_url,
                    data : {
                        'model_no' : model_no,
                        'product_name' : product_name,
                        'qty' : qty,
                        'unit_price' : unit_price,
                        'total_price' : total_price,
                        'last_po' : last_po,
                        'last_po2' : last_po2,
                        'id' : id,
                        'cur_status' : cur_status,
                        'total_hidden_price' : total_hidden_price,
                        '_token' : token
                    } ,
                    success : function(data){
                      window.location.reload();
                    }
                }); 
            }
        }
        $(v).find("#unit_price"+k).change(function(){
            chnage_total_price();
        });
        var previous_qty_value;
        $(v).find("#qty"+k).on('focus',function(){
            previous_qty_value = $(this).val();
        }).change(function(){
            if(previous_qty_value > 0){
                var unit_last_po_value = $(v).find('#last_po'+k).val()/previous_qty_value;
                var unit_last_po_scnd_value = $(v).find('#last_po2'+k).val()/previous_qty_value;
                var qty = $(v).find('#qty'+k).val();
                var last_po_value = unit_last_po_value * qty;
                var last_po_scnd_value = unit_last_po_scnd_value * qty;
                $(v).find('#last_po'+k).val(last_po_value);
                $(v).find('#last_po2'+k).val(last_po_scnd_value);
                chnage_total_price();
            }else{
                chnage_total_price();
            }
        }); 
    });

    function generatePoModel(){
        var token = "{{csrf_token()}}";
        var url = "<?= route('pdf.datasave')?>";
        var project_name = $('.project_name').val();
        var payment_terms = $('.payment_terms').val();
        var com_ship_add = $('.company_shipping_add').val();
        var sup_bil_add = $('.supplier_billing_add').val();
        var dispatch_through = $('.dispatch_through').val();
        var other_ref = $('.other_ref').val();
        var id = $('#hidden_id').val();
        var total_price = $('.hidden_total_price').val();
        var status = $('.cur_status').val();
        var spoc_name = $('.spoc_name').val();
        var email = new Array();
        var spoc_email = $('.email').val();
        email.push(spoc_email);
        var phone = new Array();
        var spoc_phone = $('.phone').val();
        phone.push(spoc_phone);
        var company_name = $('.company').val();
        var remark = $('.remark').val();
        $.ajax({
            type : 'GET',
            url : url,
            data : {
                'project_name' : project_name,
                'payment_terms' : payment_terms,
                'com_ship_add' : com_ship_add,
                'sup_bil_add' : sup_bil_add,
                'dispatch_through' : dispatch_through,
                'other_ref' : other_ref,
                'id' : id,
                'status' : status,
                'total_price' : total_price,
                '_token' : token,
                'spoc_name' : spoc_name,
                'email' : email,
                'phone' : phone ,
                'company_name' : company_name,
                'remark' : remark,
            },
            success : function(data){
                if(data.success == 'success'){
                    $('#PoModel').modal('show');
                    var id = $('#hidden_id').val();
                    var viewurl = "<?= route('pdf.showModal',':id')?>";
                    viewurl = viewurl.replace(':id',id);
                    $.get(viewurl, function(resp) {
                        $('#modal-po-info').html(resp);
                    });
                }
            },
            error : function(data){


                    console.log("Error is " +data);
            }
        });
    }
</script>
<script type="text/javascript">
    
    var tag1 = $('#multi_select').select2({
        placeholder : "Spoc Phone"
    
    });

    var tag = $('#multi_select_email').select2({
        placeholder : "Spoc Email"
    
    });

    @if(old('spoc_phone',$phoneno))
    
        var old_tag = {!! json_encode(old('spoc_phone',$phoneno)) !!};
        $.each(old_tag,function(k,v){
            $("#multi_select").append($('<option>', {value: v, text: v}));
        });

        tag1.val(old_tag).trigger('change'); 
    
    @endif
    @if(old('spoc_email',$email))
    
        var old_tag1 = {!! json_encode(old('spoc_email',$email)) !!};
        $.each(old_tag1,function(k,v){
            $("#multi_select_email").append($('<option>', {value: v, text: v}));
        });

        tag.val(old_tag1).trigger('change'); 
    
    @endif
</script>
<?=Html::script('backend/js/form.demo.min.js', [], IS_SECURE)?>
@include('admin.layout.alert')
@stop