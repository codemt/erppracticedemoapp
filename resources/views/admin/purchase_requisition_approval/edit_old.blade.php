<?php
    use \App\Http\Controllers\Admin\PurchaseRequisitionApprovalController;
?>
@extends('admin.layout.layout')
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
        <!-- <button type="submit" name="approve" value="approve_mail" class="btn btn-primary btn-sm disabled-btn" title="Approve & Send Email">Approve & Email</button> -->
        @if($update_purchase_requisition_approval_data['purchase_approval_status'] != 'onhold')
            <button type="submit" name="approve" value="onhold" class="btn btn-primary btn-sm disabled-btn" title="Hold On Order">On Hold</button>
        @endif
        <button type="submit" name="approve" value="cancel" class="btn btn-primary btn-sm disabled-btn" title="Back to users Page">Cancel</button>
    </div>
</nav>
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
                                    <input type="hidden" value="<?= $id?>" id="hidden_id">
                                    <div class="form-group @if($errors->has('company_id')) has-error @endif">
                                        <div class="col-md-12">Company Name</div>
                                        <div class="col-md-12">
                                            <?= Form::text('company_id',$company_name['company_name'],array('class' => 'form-control select_2 select company','placeholder'=>'type name')) ?>
                                            <span id="select_2_error" class="help-inline text-danger"><?=$errors->first('company_id')?></span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group @if($errors->has('supplier_id')) has-error @endif">
                                        <div class="col-md-12">Manufacturer Name</div>
                                        <div class="col-md-12">
                                            <?= Form::text('supplier_id',$supplier_name['supplier_name'],array('class' => 'form-control select_2 select supplier','placeholder'=>'type name','id' => 'supplier')) ?>
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
                                            <?= Form::text('delivery_terms',old('delivery_terms'),array('class' => 'form-control delivery_terms','placeholder'=>'Delivey Terms')) ?>
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
                                                    <input type="radio" name="currency_status" value="rupee" {{old('currency_status',$update_purchase_requisition_approval_data['currency_status']) == 'rupee' ? 'checked' : 'disabled'}} id="is_active_true" checked="checked" class="cur_status">
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
                    <div class="form-group @if($errors->has('company_id')) has-error @endif">
                        <div class="col-md-12">Distributor Name</div>
                        <div class="col-md-12">
                            <?= Form::text('distributor_id',$distributor_name['distributor_name'],array('class' => 'form-control select_2 select distributor','placeholder'=>'Distributor Name')) ?>
                            <span id="select_2_error" class="help-inline text-danger"><?=$errors->first('distributor_id')?></span>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group @if($errors->has('remark')) has-error @endif">
                        <div class="col-md-12">Remark</div>
                        <div class="col-md-12">
                            <?= Form::textarea('remark',old('delivery_terms'),array('class' => 'form-control remark','placeholder'=>'Remark','rows'=>'5')) ?>
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
                                        <th style="border:none">QTY</th>
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
                                            <?= Form::number('qty','1', ['class' => 'form-control qty','id'=>'qty','style'=>'width:120px','min'=>1]); ?>
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
                                    <td colspan="4" align="right" style="border: none;font-weight: bold;font-size: 16px">Total price :</td>
                                    <td style="border: none;font-weight: bold;font-size: 16px">
                                        <input type="text" class="hidden_total_price" value="<?= $total_calculation?> @if($update_purchase_requisition_approval_data['currency_status'] == 'dollar'){{'USD'}}@else{{'INR'}} @endif" style="border:none;width: 120px;" readonly>
                                    </td>
                                    <td colspan="2" align="right" style="border: none;font-weight: bold;font-size: 16px">
                                        <a href="<?= route('purchase.requisition-approval.exportPrItem',$id)?>" type="button" class="btn btn-default btn-sm" title="Export to CSV">Export</button>
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
                    <h4 class="title">Purchase Requisition Approval Other Dteails</h4>
            </div><hr>
            <div class="row">
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
        <button type="submit" name="approve" value="approve" class="btn btn-primary btn-sm disabled-btn" title="Generate PO & Send">Generate PO & Send </button>
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
                <h4 class="modal-title" style="text-align: center">Puchase Order</h4>
            </div>
            <div class="modal-body box no-border">
                <div id="modal-po-info"></div>
            </div>
            <div class="modal-footer">
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
    if(status == 'pending'){
        $('.qty').prop('readonly',true);
    }
    $(document).ready(function () {
        var list = $('tbody tr');
            ajaxCall();
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
                        // console.log(data);
                        $.each(data,function(i,o){
                            // toappend +='<option>'+o+'</option>';
                            $("#product_name"+k).val(data.name_description);
                        });
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
        placeholder : "Select Supplier Address",
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
            }
        });
    });
    function selectPayment(payment_value){
        // console.log(payment_value);
        $('.payment_terms').val(payment_value);
        $('#payment_suggetion').hide();
    }
    var total_amount = 0;
    $.each(select_list,function(k,v){
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
            var unit_last_po_value = $(v).find('#last_po'+k).val()/previous_qty_value;
            var unit_last_po_scnd_value = $(v).find('#last_po2'+k).val()/previous_qty_value;
            var qty = $(v).find('#qty'+k).val();
            var last_po_value = unit_last_po_value * qty;
            var last_po_scnd_value = unit_last_po_scnd_value * qty;
            $(v).find('#last_po'+k).val(last_po_value);
            $(v).find('#last_po2'+k).val(last_po_scnd_value);
            chnage_total_price();
        }); 
    });

    function generatePoModel(){
        $('#PoModel').modal('show');
        var id = $('#hidden_id').val();
        var viewurl = "<?= route('pdf.showModal',':id')?>";
        viewurl = viewurl.replace(':id',id);
        $.get(viewurl, function(resp) {
            $('#modal-po-info').html(resp);
        });
    }
</script>
@include('admin.layout.alert')
@stop