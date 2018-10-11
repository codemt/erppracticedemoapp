@extends('admin.layout.layout')
@section('start_form')
    <?=Form::model($purchase_requisition_data,['method' => 'POST', 'route'=>['purchase.store'], 'class' => 'm-0 form-horizontal'])?>
@stop
@section('top_fixed_content')
<meta name="_token" content="{{csrf_token()}}" />
<nav class="navbar navbar-static-top">
    <div class="title">
         <h4></h4>
    </div>
    <div class="pl-10">
        <button type="submit" name="save_new" value="save_new" class="btn btn-primary btn-sm disabled-btn" title="Save and add new"> Save & New   </button>
        <button type="submit" name="save_button" value="save_exit" class="btn btn-primary btn-sm disabled-btn" title="Save and exit"> Save  & Exit </button>
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
                {{-- <input type="hidden" name="id" value="{{ $id }}" id="hidden_id"> --}}
                    <div class="row">
                        <div class="col-md-4">
                            <input type="hidden" name="company_id" id="company_id" value="{{ $purchase_requisition_data['company_id'] }}">
                            <input type="hidden" name="supplier_id" id="supplier_id" value="{{ $purchase_requisition_data['supplier_id'] }}">
                            <input type="hidden" name="distributor_id" id="distributor_id" value="{{ $purchase_requisition_data['distributor_id'] }}">
                            <div class="form-group @if($errors->has('company_id')) has-error @endif">
                                <div class="col-md-12">Select Company</div>
                                <div class="col-md-12">
                                    <?= Form::select('company_id',$company_list, old('company_id',$purchase_requisition_data['company_id']),array('class' => 'form-control select_2 select company','placeholder'=>'Select Company','name'=>'company_name','id'=>'company_name')) ?>
                                    <span id="select_2_error" class="help-inline text-danger"><?=$errors->first('company_id')?></span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group @if($errors->has('supplier_id')) has-error @endif">
                                <div class="col-md-12">Select Manufacturer</div>
                                <div class="col-md-12">
                                    <?= Form::select('supplier_id',$supplier_list, old('supplier_id',$purchase_requisition_data['supplier_id']),array('class' => 'form-control select_2 select supplier','placeholder'=>'Select Manufacturer','name'=>'supplier_id','id' => 'supplier')) ?>
                                    <span id="select_2_error" class="help-inline text-danger"><?=$errors->first('supplier_id')?></span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group @if($errors->has('distributor_id')) has-error @endif">
                                <div class="col-md-12">Select Distributor</div>
                                <div class="col-md-12">
                                    <?= Form::select('distributor_id',$distributor_list, old('distributor_id'),array('class' => 'form-control select_2 select distributor','placeholder'=>'Select Distributor','name'=>'distributor_id','id' => 'distributor')) ?>
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
                                    <?= Form::text('delivery_terms',old('delivery_terms'),array('class' => 'form-control','placeholder'=>'Delivey Terms','id'=>'delivery_terms')) ?>
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
                                            <input type="radio" name="currency_status"  value="rupee" {{old('currency_status',$purchase_requisition_data['currency_status']) == 'rupee' ? 'checked' : ''}} id="is_active_true" checked="checked">
                                            <span class="label-text"></span> Rupee
                                        </label>
                                    </div>
                                    <div class="animated-radio-button pull-left">
                                        <label class="control-label" for="is_active_false">
                                            <input type="radio" name="currency_status"  value="dollar" {{old('currency_status',$purchase_requisition_data['currency_status']) == 'dollar' ? 'checked' : ''}} id="is_active_false">
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
                            <table class="table m-0 v-top ">
                                <thead>
                                    <tr class="row">
                                        <th class="col-md-3" style="border:none">Model No<sup class="text-danger">*</sup></th>
                                        <th class="col-md-3" style="border:none">Product Name</sup></th>
                                        <th class="col-md-3" style="border:none">QTY<sup class="text-danger">*</sup></th>
                                        <th class="col-md-3" style="border:none"></th>
                                        <th></th>
                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr id="shipping" class="row">
                                        <td class="col-md-3" style="border:none;padding-left: 0">
                                            <?= Form::select('model_no',$model_no_list,old('model_no',$purchase_requisition_data['model_no']), ['class' => 'form-control select2 model_no','id'=>'model_no','style'=>'width:247px']); ?>
                                            <span id="model_no_error" class="help-inline text-danger"><?= $errors->first('shipping.shipping.model_no') ?></span>
                                        </td>   
                                        <td class="col-md-3" style="border:none">
                                            <?= Form::text('product_name',old('product_name'),['class' => 'form-control product_name','id'=>'product_name','style'=>'width:247px','readonly'=>'readonly']); ?>
                                            <span id="product_name_error" class="help-inline text-danger"><?= $errors->first('shipping.shipping.product_name') ?></span>
                                        </td>
                                        <td class="col-md-3" style="border:none">
                                            <?= Form::number('qty','1', ['class' => 'form-control qty_only','id'=>'qty','style'=>'width:247px','min'=>1]); ?>
                                            <span id="qty_error" class="help-inline text-danger"><?= $errors->first('shipping.shipping.qty') ?></span>
                                        </td>
                                        <td class="col-md-3" style="border:none">
                                            <a id="shipping_remove" class="pt-10 pull-left btn-remove" onclick="ajaxCall()"><i class="fa fa-minus-circle fa-small pull-left"></i></a>
                                            <a id="shipping_add" class="pt-10 pull-left btn-add" onclick="btnAdd()"><i class="fa fa-plus-circle fa-small pull-left" ></i></a>
                                        </td>
                                        <td class="col-md-3" style="border:none;padding-left: 0">
                                            <?= Form::hidden('unit_price',old('unit_price'), ['class' => 'form-control unit_price','id'=>'unit_price']); ?>
                                            <span id="id_error" class="help-inline text-danger"><?= $errors->first('shipping.shipping.unit_price') ?></span>
                                        </td>
                                        <td class="col-md-3" style="border:none;padding-left: 0">
                                            <?= Form::hidden('id',old('id'), ['class' => 'form-control id','id'=>'id']); ?>
                                            <span id="id_error" class="help-inline text-danger"><?= $errors->first('shipping.shipping.id') ?></span>
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
    <button type="submit" name="save_new" value="save_new" class="btn btn-primary btn-sm disabled-btn" title="Save and add new">Save & New  </button>
    <button type="submit" name="save_button" value="save_new" class="btn btn-primary btn-sm disabled-btn" title="Save & exit">Save & Exit </button>
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
    // console.log($('.company').val());
        $('.company').prop('disabled',true);
      //  $('.company').prop('disabled',true);
        $('.supplier').prop('disabled',true);
        $('.distributor').prop('disabled',true);
        if($('.company').val() == "" || $('.supplier').val() == ""){
            $('.model_no').prop('disabled',true);
        }
        
        var list = $('tbody tr');
            ajaxCall();

             $('#re_order').click(function(e){

            e.preventDefault();
            var reorderId = $('#reorder').val();
          //  console.log("Re Order ID " +reorderId);
            var company_id = $('#company_name').val();
            var supplier = $('#supplier').val();
            var distributor_name = $('#distributor_name').val();
            var delivery_terms = $('#delivery_terms').val();
            var currency = $('#is_active_true').val();
            var model_no = $('#shipping .model_no').val();
            var product_name = $('#shipping .product_name').val();

            var qty = $('#shipping .qty').val();
            console.log("Company Name is " +company_id);
            console.log('supplier is ' +supplier);
            console.log('Delivery Terms ' +delivery_terms);
            console.log('currency is ' +currency);
            console.log('Distributor Name is ' +distributor_name);
           // console.log(company_id,supplier,delivery_terms,currency,distributor_name);
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
        
        /*
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

        */

});

        });

        function btnAdd(){

            $(this).delay(10).queue(function() {

                $(this).hide();
                ajaxCall();
                
                $(this).dequeue();
                var company_value = $('.company').val();
                var supplier_value = $('.supplier').val();
                var token = "{{csrf_token()}}";
                var id = $("#hidden_id").val();
                var url = "{{route('purchase-requisition.edit',':id')}}";
                btnAdd_model_call(token,supplier_value,company_value,url);
                
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
                    // console.log(model_no);
                    if(model_no != null){
                        var id = $("#hidden_id").val();
                        var token = "{{csrf_token()}}";
                        var url = "{{route('purchase-requisition.edit',':id')}}";
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
                    }
                    else{
                        $("#product_name"+k).text('');
                    }
                }); 
            });
        }
        var customer_shipping_detail =  $("#shipping").dynamicForm("#shipping_add", "#shipping_remove", {
            limit: 10,
            normalizeFullForm : false,
        });

        old_data = <?= json_encode(old('shipping.shipping',$purchase_requisition_detail)) ?>;

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
        $('.model_no').select2({
            placeholder : "Select Model No",
        });
        $("#is_active_false").click(function(){
            $(this).prop('checked','checked');
        });
       
       function model_ajax(token,supplier_value,company_value,url){


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
                    var toappend = '';
                    $.each(data,function(i,o){
                        toappend +='<option>'+o+'</option>';
                    })

                    $.each(select_list,function(k,v){
                        var model = $(v).find('#model_no'+k).val();
                        $(v).find('#model_no'+k).html('');
                        $(v).find('#model_no'+k).append(toappend);
                        $(v).find('#model_no'+k).val(model).trigger('change'); 
                    });
                }
            });
        }
        function btnAdd_model_call(token,supplier_value,company_value,url){

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
                    var toappend = '';
                    $.each(data,function(i,o){
                        toappend +='<option>'+o+'</option>';
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
        if($('.company').val() != '' && $('.supplier').val() != ''){
            // alert($(this).val());
            var company_value = $('.company').val();
            var supplier_value = $('.supplier').val();
            var token = "{{csrf_token()}}";
            var id = $("#hidden_id").val();
            var url = "{{route('purchase-requisition.edit',':id')}}";
            model_ajax(token,supplier_value,company_value,url);
        }
    </script>
@include('admin.layout.alert')
@stop