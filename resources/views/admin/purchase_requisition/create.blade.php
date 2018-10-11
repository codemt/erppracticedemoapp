@extends('admin.layout.layout')
@section('start_form')
    <?=Form::open(['method' => 'POST', 'route'=>'purchase-requisition.store', 'class' => 'm-0 form-horizontal','files'=>true])?>
@stop
@section('top_fixed_content')
<nav class="navbar navbar-static-top">
    <div class="title">
         <h4></h4>
    </div>
    <div class="pl-10">
        <button type="submit" name="save_new" value="save_new" class="btn btn-primary btn-sm disabled-btn" title="Save and add new">Save & New </button>
        <button type="submit" name="save_button" value="save_exit" class="btn btn-primary btn-sm disabled-btn" title="Save and exit">Save & exit </button>
        <a href="<?= route('purchase-requisition.index')?>" class="btn btn-default btn-sm" title="Back to users Page">Cancel</a>
    </div>
</nav>
@stop
@section('content')
<div class="row">
    <div class="col-md-12 mb-30">
        <div class="card">
            <div class="card-title-w-btn">
                    <h4 class="title">Purchase Requisition</h4>
            </div><hr>
            <div class="row">
                <div class="col-md-12">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group @if($errors->has('company_id')) has-error @endif">
                                <div class="col-md-12">Select Company<sup class="text-danger">*</sup></div>
                                <div class="col-md-12">
                                    <?= Form::select('company_id',$company_list, old('company_id'),array('class' => 'form-control select_2 select company','placeholder'=>'Select Company','id'=>'company')) ?>
                                    <span id="select_2_error" class="help-inline text-danger"><?=$errors->first('company_id')?></span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group @if($errors->has('supplier_id')) has-error @endif">
                                <div class="col-md-12">Select Manufacturer<sup class="text-danger">*</sup></div>
                                <div class="col-md-12">
                                    <?= Form::select('supplier_id',$supplier_list, old('supplier_id'),array('class' => 'form-control select_2 select supplier','placeholder'=>'Select Manufacturer','id' => 'supplier')) ?>
                                    <span id="select_2_error" class="help-inline text-danger"><?=$errors->first('supplier_id')?></span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group @if($errors->has('distributor_id')) has-error @endif">
                                <div class="col-md-12">Select Distributor</div>
                                <div class="col-md-12">
                                    <?= Form::select('distributor_id',$distributor_list, old('distributor_id'),array('class' => 'form-control select_2 select distributor','placeholder'=>'Select Distributor','id' => 'distributor')) ?>
                                    <span id="select_2_error" class="help-inline text-danger"><?=$errors->first('distributor_id')?></span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-12">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group @if($errors->has('delivery_terms')) has-error @endif">
                                <div class="col-md-12">Delivery Terms<sup class="text-danger">*</sup></div>
                                <div class="col-md-12">
                                    <?= Form::text('delivery_terms',old('delivery_terms'),array('class' => 'form-control','placeholder'=>'Delivey Terms')) ?>
                                    <span id="select_2_error" class="help-inline text-danger"><?=$errors->first('delivery_terms')?></span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group @if($errors->has('currency_status')) {{ 'has-error' }} @endif">
                                <div class="col-md-12">Rupee/Dollar</div>
                                <div class="col-md-12">
                                    <div class="animated-radio-button pull-left mr-10">
                                        <label class="control-label" for="is_active_true">
                                            <input type="radio" name="currency_status" value="rupee" {{old('currency_status') == 'rupee' ? 'checked' : ''}} id="is_active_true" checked="checked" class="cur_status">
                                            <span class="label-text"></span> Rupee
                                        </label>
                                    </div>
                                    <div class="animated-radio-button pull-left">
                                        <label class="control-label" for="is_active_false">
                                            <input type="radio" name="currency_status" value="dollar" {{old('currency_status') == 'dollar' ? 'checked' : ''}} id="is_active_false" class="cur_status">
                                            <span class="label-text"></span> Dollar
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-12">
                    <div class="form-group row">
                        <div class="col-md-12">
                            <table class="table m-0 v-top vertical-align">
                                <thead>
                                    <tr class="row">
                                        <th class="col-md-3" style="border:none">Model No<sup class="text-danger">*</sup></th>
                                        <th class="col-md-3" style="border:none">Product Name</sup></th>
                                        <th class="col-md-3" style="border:none">QTY<sup class="text-danger">*</sup></th>
                                        <th class="col-md-3" style="border:none"></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr id="shipping" class="row">
                                        <td valign="top" class="col-md-3" style="border:none;padding-left: 0;">
                                            <?= Form::select('model_no',$model_no_list,old('model_no'), ['class' => 'form-control select2 model_no','id'=>'model_no','style'=>'width:247px']); ?>
                                            <strong><small class="form-text text-muted">Company name , Manufacturer name must required for model no.</small></strong><br>
                                            <span id="model_no_error" class="help-inline text-danger"><?= $errors->first('shipping.shipping.model_no') ?></span>
                                        </td>
                                        <td valign="top"  class="col-md-3" style="border:none;">
                                            <?= Form::text('product_name',old('product_name'),['class' => 'form-control product_name','id'=>'product_name','style'=>'width:247px','readonly'=>'readonly']); ?>
                                            <span id="product_name_error" class="help-inline text-danger"><?= $errors->first('shipping.shipping.product_name') ?></span>
                                        </td>
                                        <td valign="top" class="col-md-3" style="border:none;">
                                            <?= Form::number('qty','1', ['class' => 'form-control qty_only','id'=>'qty','style'=>'width:247px','min'=>1]); ?>
                                            <span id="qty_error" class="help-inline text-danger"><?= $errors->first('shipping.shipping.qty') ?></span>
                                        </td>
                                        <td valign="top" class="col-md-3" style="border:none;">
                                            <a id="shipping_remove" class="pt-10 pull-left btn-remove" onclick="ajaxCall()"><i class="fa fa-minus-circle fa-small pull-left"></i></a>
                                            <a id="shipping_add" class="pt-10 pull-left btn-add" onclick="btnAdd()"><i class="fa fa-plus-circle fa-small pull-left" ></i></a>
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
    </div>
</div>
<div class="text-right">
    <button type="submit" name="save_new" value="save_new" class="btn btn-primary btn-sm disabled-btn" title="Save and add new user">Save & New </button>
        
    <button type="submit" name="save_button" value="save_new" class="btn btn-primary btn-sm disabled-btn" title="Save & exit">Save & exit</button>
    <a href="<?= route('purchase-requisition.index')?>" class="btn btn-default btn-sm" title="Back to user Page">Cancel</a>
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

    $(document).ready(function () {
        // console.log($('.supplier').val());
        if($('.company').val() == "" && $('.supplier').val() == ""){
            $('.model_no').prop('disabled',true);
        }
        if($('.company').val() == ''){
            $('.supplier').prop('disabled',true);
        }
        var list = $('tbody tr');
        ajaxCall();

        });

        function btnAdd(){
            $(this).delay(10).queue(function() {

                $(this).hide();
                
                ajaxCall();
                
                $(this).dequeue();
                var model_array = [];
                $.each(select_list,function(k,v){
                    model_array.push($(v).find('#model_no'+k).val());
                });
                var company_value = $('.company').val();
                var supplier_value = $('.supplier').val();
                var token = "{{csrf_token()}}";
                var url = "{{route('purchase-requisition.create')}}";
                btnAdd_model_call(token,supplier_value,company_value,url,model_array);
            }); 
        }
        function ajaxCall(){
            select_list = $('tbody tr');
            // console.log(select_list);
            $.each(select_list,function(k,v){
                $(v).find('#model_no'+k).select2({
                    placeholder : 'Select model no'
                });
                // $(v).find('#product_name'+k).select2({
                //     placeholder : 'Select product name'
                // });
                // $(v).find('#qty'+k).select2({
                //     placeholder : 'Select model_no'
                // });
                $(v).find("#model_no"+k).change(function(){
                    var model_no = $(this).val();
                    // alert($(this).val());
                    var token = "{{csrf_token()}}";
                    var url = "<?php route('purchase-requisition.create')?>";
                    $.ajax({
                        type : 'GET',
                        url : url,
                        data : {
                            'model_no' : model_no,
                            '_token' : token
                        },
                        success : function(data){
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

        old_data = <?= json_encode(old('shipping.shipping')) ?>;

        customer_shipping_detail.inject(old_data);

        // var select_list = $('tbody tr');

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
            });
        @endif
        $('.company').select2({
            placeholder : "Select Company",
        });
        $('.supplier').select2({
            placeholder : "Select Manufacturer",
        });
        $('.distributor').select2({
            placeholder : "Select Distributor",
        });
        $("#is_active_false").click(function(){
            $(this).prop('checked','checked');
        });
        function model_ajax(token,supplier_value,company_value,url,model_array){
           var select_list = $('tbody tr');
            $.each(select_list,function(k,v){
                // $('#model_no'+k).val();
                // $('#product_name'+k).val();
                var model_old = $('#model_no'+k).val();
                $('.company').change(function(){
                    $('#model_no'+k).val('').trigger('change');
                    $('#product_name'+k).val('').trigger('change');
                    $('#qty'+k).val('1').trigger('change');
                });
                $('.supplier').change(function(){
                    $('#model_no'+k).val('').trigger('change');
                    $('#product_name'+k).val('').trigger('change');
                    $('#qty'+k).val('1').trigger('change');
                });
                if(company_value != '' && supplier_value != ''){
                    $.ajax({
                        type : 'GET',
                        url : url,
                        data : {
                            'supplier_id' : supplier_value,
                            'company_name' : company_value,
                            '_token' : token    
                        },
                        success : function(data){
                            $('.model_no').prop('disabled',false);
                            // $('.model_no').html('');
                            var toappend = '';
                            $.each(data,function(i,o){
                                toappend +='<option>'+o+'</option>';
                            })
                            // $('.model_no').append(toappend);
                            $.each(select_list,function(k,v){
                                model = $(v).find('#model_no'+k).val();
                                $(v).find('#model_no'+k).html('');
                                $(v).find('#model_no'+k).append(toappend);
                                $(v).find('#model_no'+k).val(model).trigger('click'); 
                            });
                        }
                    });
                }
                else{
                    $('.model_no').prop('disabled',true);
                }
            });
        }
        function btnAdd_model_call(token,supplier_value,company_value,url,model_array){
           var select_list = $('tbody tr');
           $.each(select_list,function(k,v){
             var model_old = $('.model_no').val();
             $('.supplier').change(function(){
                 $('#model_no'+k).val('').trigger('change');
                 $('#product_name'+k).val('').trigger('change');
                 $('#qty'+k).val('1').trigger('change');
            });
            $('.company').change(function(){
                $('#model_no'+k).val('').trigger('change');
                $('#product_name'+k).val('').trigger('change');
                $('#qty'+k).val('1').trigger('change');
            });
            row_length = ((select_list).length)+1;
            if(company_value != '' && supplier_value != ''){
                $.ajax({
                    type : 'GET',
                    url : url,
                    data : {
                        'supplier_id' : supplier_value,
                        'company_name' : company_value,
                        'data' : model_array,
                        '_token' : token    
                    },
                    success : function(data){
                        $('.model_no').prop('disabled',false);
                        var toappend = '';
                        $.each(data,function(i,o){
                            toappend +='<option>'+o+'</option>';
                            // console.log(o);
                        })

                        $.each(select_list,function(k,v){
                            model = $(v).find('#model_no'+k).val();
                            $(v).find('#model_no'+k).html('');
                            $(v).find('#model_no'+k).append(toappend);
                            $(v).find('#model_no'+k).val(model).trigger('click'); 
                        });
                    }
                });
            }
            else{
                $('.model_no').prop('disabled',true);
            }
          });
        }
        function supplier_list(token,company_value){

            $('.company').change(function(){
                $('#supplier').val('').trigger('change');
                $('.model_no').prop('disabled',true);
            });
            var token = token;
            var company_value = company_value;
            var url = url;
            $.ajax({
                url : url,
                type : 'GET',
                data : {
                    '_token' : token,
                    'company_value' : company_value
                },
                success : function(data){
                    $('.supplier').prop('disabled',false);
                    var toappend = '';
                    $.each(data,function(i,o){
                        toappend +='<option>'+o+'</option>';
                        // console.log(o);
                    })

                    $.each(select_list,function(k,v){
                        model = $('.supplier').val();
                        $('#supplier').html('');
                        $('#supplier').append(toappend);
                        $('#supplier').val(model).trigger('click'); 
                    });
                }
            })
        }
        $('.company').change(function(){
             var model_array = [];
                $.each(select_list,function(k,v){
                    model_array.push($(v).find('#model_no'+k).val());
                });
            var company_value = $('.company').val();
            var supplier_value = $('.supplier').val();
            var token = "{{csrf_token()}}";
            var url = "<?= route('purchase-requisition.create')?>";
            supplier_list(token,company_value,url);
            model_ajax(token,supplier_value,company_value,url);
            // alert(supplier_value);
        });
        $('.supplier').change(function(){
            // alert($(this).val());
             var model_array = [];
                $.each(select_list,function(k,v){
                    model_array.push($(v).find('#model_no'+k).val());
                });
            var company_value = $('.company').val();
            var supplier_value = $('.supplier').val();
            var token = "{{csrf_token()}}";
            var url = "<?= route('purchase-requisition.create')?>";
            model_ajax(token,supplier_value,company_value,url);
            // alert(supplier_value);
        });
        if($('.company').val() != '' && $('.supplier').val() != ''){
            // alert($(this).val());
             var model_array = [];
                $.each(select_list,function(k,v){
                    model_array.push($(v).find('#model_no'+k).val());
                });
            var company_value = $('.company').val();
            var supplier_value = $('.supplier').val();
            var token = "{{csrf_token()}}";
            var url = "{{route('purchase-requisition.create')}}";
            model_ajax(token,supplier_value,company_value,url);
        }
    </script>
@include('admin.layout.alert')
@stop