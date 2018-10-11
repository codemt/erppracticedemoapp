@extends('admin.layout.layout')
@section('style')
    <?=Html::style('backend/css/jquery.fileuploader.css')?>
    <?=Html::style('backend/css/jquery.fileuploader-theme-thumbnails.css')?>
    <?=Html::style('backend/css/bootstrap-fileupload.css')?>
@stop
@section('start_form')
    <?=Form::model($edit_product_data,['method' => 'PATCH', 'route'=>['product.update',$id], 'class' => 'm-0 form-horizontal','files'=>true])?>
@stop
@section('top_fixed_content')
<nav class="navbar navbar-static-top">
    <div class="title">
         <h4></h4>
    </div>
    <div class="pl-10">
        <button type="submit" name="save_new" value="save_new" class="btn btn-primary btn-sm disabled-btn" title="Save and add new">Save </button>
        <button type="submit" name="save_button" value="save_exit" class="btn btn-primary btn-sm disabled-btn" title="Save and exit">Save & exit </button>
        <a href="<?= route('product.index')?>" class="btn btn-default btn-sm" title="Back to users Page">Cancel</a>
    </div>
</nav>
@stop
@section('content')
<div class="row">
    <div class="col-md-12 mb-30">
        <div class="card">
            <div class="card-title-w-btn">
                    <h4 class="title">Edit Product Master</h4>
            </div><hr>
            <div class="row">
                <div class="col-md-12">
                    <input type="hidden" name="id" value="<?= $id?>" id="hidden_id">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group @if($errors->has('company_id')) has-error @endif">
                                <div class="col-md-12">Select Company<sup class="text-danger">*</sup></div>
                                <div class="col-md-12">
                                    <?= Form::select('company_id',$company_list, old('company_id'),array('class' => 'form-control select_2 company','placeholder'=>'type name')) ?>
                                    <span id="select_2_error" class="help-inline text-danger"><?=$errors->first('company_id')?></span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group @if($errors->has('supplier_id')) has-error @endif">
                                <div class="col-md-12">Select Manufacturer<sup class="text-danger">*</sup></div>
                                <div class="col-md-12">
                                    <?= Form::select('supplier_id',$supplier_list, old('supplier_id'),array('class' => 'form-control select_2 supplier','id'=>'edit_supplier','placeholder'=>'type name')) ?>
                                    <span id="select_2_error" class="help-inline text-danger"><?=$errors->first('supplier_id')?></span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group @if($errors->has('product_type')) has-error @endif">
                                <div class="col-md-12">Select Product Type<sup class="text-danger">*</sup></div>
                                <div class="col-md-12">
                                    <?= Form::select('product_type',Config::get('product_type'), old('product_type'),array('class' => 'form-control select_2 product_type','id'=>'select_2','placeholder'=>'type name')) ?>
                                    <span id="select_2_error" class="help-inline text-danger"><?=$errors->first('product_type')?></span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-12">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group @if($errors->has('combo_product')) has-error @endif">
                                <div class="col-md-12">Select Products<sup class="text-danger">*</sup></div>
                                <div class="col-md-12">
                                    <?= Form::select('combo_product[]',$product_list,old('combo_product',$edit_product_data['combo_product']),array('class' => 'form-control select2 combo_product','id'=>'multi_select','multiple'=>true)) ?>
                                    <strong><small class="form-text text-muted">Company name , Supplier name and Product type(bundle) must required for selecting products.
                                    </small></strong><br>
                                    <span id="mul_select_error" class="help-inline text-danger"><?=$errors->first('combo_product')?></span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-12">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group @if($errors->has('model_no')) {{ 'has-error' }} @endif">
                                <div class="col-md-12">Model Number<sup class="text-danger">*</sup></div>
                                <div class="col-md-12">
                                    <?=Form::text('model_no', null, ['class' => 'form-control', 'placeholder' => 'Model Number','maxlength'=>'100']);?>
                                    <span id="text_box_error" class="help-inline text-danger"><?=$errors->first('model_no')?></span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group @if($errors->has('name_description')) {{ 'has-error' }} @endif">
                                <div class="col-md-12">Name & Description<sup class="text-danger">*</sup></div>
                                <div class="col-md-12">
                                    <?=Form::text('name_description',null, ['class' => 'form-control', 'placeholder' => 'Name & Description']);?>
                                    <span id="text_box_error" class="help-inline text-danger"><?=$errors->first('name_description')?></span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group @if($errors->has('price')) {{ 'has-error' }} @endif">
                                <div class="col-md-12">Price<sup class="text-danger">*</sup></div>
                                <div class="col-md-12">
                                    <?=Form::text('price',old('price'), ['class' => 'form-control price number_only', 'placeholder' => 'Price']);?>
                                    <span id="text_box_error" class="help-inline text-danger"><?=$errors->first('price')?></span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-12">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group @if($errors->has('unit')) {{ 'has-error' }} @endif">
                                <div class="col-md-12">Units in  mtr/lott/no</div>
                                <div class="col-md-12">
                                    <?=Form::text('unit', null, ['class' => 'form-control number_only', 'placeholder' => 'Units in  mtr/lott/no']);?>
                                    <span id="text_box_error" class="help-inline text-danger"><?=$errors->first('unit')?></span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group @if($errors->has('max_discount')) {{ 'has-error' }} @endif">
                                <div class="col-md-12">Maximum Discount Applied<sup class="text-danger">*</sup></div>
                                <div class="col-md-12">
                                    <?=Form::text('max_discount', null, ['class' => 'form-control number_only', 'placeholder' => 'Maximum Discount Applied']);?>
                                    <span id="text_box_error" class="help-inline text-danger"><?=$errors->first('max_discount')?></span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group @if($errors->has('tax')) has-error @endif">
                                <div class="col-md-12">Tax<sup class="text-danger">*</sup></div>
                                <div class="col-md-12">
                                    <?= Form::select('tax',[''=>'Select Tax'] + Config::get('tax'),old('tax'),array('class' => 'form-control select_2 tax','placeholder'=>'type name')) ?>
                                    <span id="select_2_error" class="help-inline text-danger"><?=$errors->first('tax')?></span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-12">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group @if($errors->has('file')) {{ 'has-error' }} @endif">
                                <div class="col-md-12">Image</div>
                                <div class="col-md-12">
                                    <div class="fileupload fileupload-new" data-provides="fileupload">
                                        <div class="fileupload-new thumbnail" style="max-width: 190px; max-height: 150px; line-height: 20px;">
                                            @if(empty($edit_product_data['image']))
                                                <img src="<?=asset('backend/images/no_image.png', IS_SECURE)?>" alt="" />
                                            @else
                                                <img src="<?=asset(LOCAL_IMAGE_PATH.'products/'.$edit_product_data['image'], IS_SECURE)?>" alt="" />
                                            @endif
                                        </div>
                                        <div class="fileupload-preview fileupload-exists thumbnail" style="max-width: 190px; max-height: 150px; line-height: 20px;"></div>
                                        <div>
                                            <label class="btn btn-file">
                                                <?=Form::file('image', ['class' => 'form-control'])?>
                                                <span  class="fileupload-new"><i class="fa fa-paper-clip"></i> Select image</span>
                                                <span  class="fileupload-exists"><i class="fa fa-undo"></i> Change</span>
                                            </label>
                                            <a href="" class="btn btn-danger fileupload-exists" data-dismiss="fileupload"><i class="fa fa-trash"></i> Remove</a>
                                        </div>
                                        <strong><small class="form-text text-muted">
                                            The image dimension must be 200x200.
                                        </small></strong><br>
                                        <span id="image_error" class="help-inline text-danger"><?=$errors->first('image')?></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-8">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group @if($errors->has('hsn_code')) {{ 'has-error' }} @endif">
                                        <div class="col-md-12">HSN Code<sup class="text-danger">*</sup></div>
                                        <div class="col-md-12">
                                            <?=Form::text('hsn_code', null, ['class' => 'form-control', 'placeholder' => 'HSN Code','maxlength'=>'9']);?>
                                            <span id="text_box_error" class="help-inline text-danger"><?=$errors->first('hsn_code')?></span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group @if($errors->has('weight')) {{ 'has-error' }} @endif">
                                        <div class="col-md-12">Weight</div>
                                        <div class="col-md-12">
                                            <?=Form::text('weight', null, ['class' => 'form-control number_only', 'placeholder' => 'Weight']);?>
                                            <span id="text_box_error" class="help-inline text-danger"><?=$errors->first('weight')?></span>
                                        </div>
                                    </div>
                                </div> 
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group @if($errors->has('qty')) {{ 'has-error' }} @endif">
                                        <div class="col-md-12">Qty<sup class="text-danger">*</sup></div>
                                        <div class="col-md-12">
                                            <?=Form::text('qty', null, ['class' => 'form-control qty_only', 'placeholder' => 'Qty','maxlength'=>'10']);?>
                                            <span id="text_box_error" class="help-inline text-danger"><?=$errors->first('qty')?></span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group @if($errors->has('min_qty')) {{ 'has-error' }} @endif">
                                        <div class="col-md-12">Minimum QTY to maintain<sup class="text-danger">*</sup></div>
                                        <div class="col-md-12">
                                            <?=Form::text('min_qty', null, ['class' => 'form-control qty_only', 'placeholder' => 'Minimum QTY to maintain','maxlength'=>'10']);?>
                                            <span id="text_box_error" class="help-inline text-danger"><?=$errors->first('min_qty')?></span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group @if($errors->has('address')) {{ 'has-error' }} @endif">
                                        <div class="col-md-12">Enable/Disable Product</div>
                                        <div class="col-md-12">
                                            <div class="animated-radio-button pull-left mr-10">
                                                <label class="control-label" for="is_active_true">
                                                    <input type="radio" name="product_status" value="1" {{old('product_status',$edit_product_data['product_status']) == '1' ? 'checked' : ''}} id="is_active_true" checked="checked">
                                                    <span class="label-text"></span> Enable
                                                </label>
                                            </div>
                                            <div class="animated-radio-button pull-left">
                                                <label class="control-label" for="is_active_false">
                                                    <input type="radio" name="product_status" value="0" {{old('product_status',$edit_product_data['product_status']) == '0' ? 'checked' : ''}} id="is_active_false">
                                                    <span class="label-text"></span> Disable
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @include('admin.layout.overlay')
        </div>
    </div>
</div>
<div class="text-right">
    <button type="submit" name="save_new" value="save_new" class="btn btn-primary btn-sm disabled-btn" title="Save and add new user">Save</button>
        
    <button type="submit" name="save_button" value="save_new" class="btn btn-primary btn-sm disabled-btn" title="Save & exit">Save & exit</button>
    <a href="<?= route('product.index')?>" class="btn btn-default btn-sm" title="Back to user Page">Cancel</a>
</div>
@stop

@section('end_form')
<?=Form::close()?>
@stop
@section('script')
<?= Html::script('backend/js/select2.min.js',[],IS_SECURE) ?>
<?= Html::script('backend/js/bootstrap-fileupload.js',[],IS_SECURE) ?>

    <script type="text/javascript">
        $(document).ready(function(){
            $('#multi_select').prop('disabled',true);
            $('.company').select2({
                placeholder : "Select Company",
            });
            $('.supplier').select2({
                placeholder : "Select Supplier",
            });
            $('.product_type').select2({
                placeholder : "Select Product Type",
            });
            var tagsControlProduct = $('.combo_product').select2({
                placeholder : "Select Products",
            });
            @if(old('combo_product',$edit_product_data['combo_product']))
                var combo_product = {!! json_encode(old('combo_product',$edit_product_data['combo_product'])) !!};
                // console.log(combo_product);
                if(typeof combo_product == 'string'){
                    combo_product = combo_product.split(',');
                }
                $.each(combo_product,function(k,v){
                    $(".combo_product").append($('<option>', {value: v, text: v}));
                })
                tagsControlProduct.val(combo_product).trigger('change');
            @endif
            $('.tax').select2({
                placeholder : "Select Tax",
            });
            if($('.product_type').val() == 'single'){
                $('#multi_select').prop('disabled',true);
                $('#multi_select').val('').trigger('change');
                // $('.price').prop('readonly',false);
            }
            if($('.product_type').val() == 'bundle'){
                $('#multi_select').prop('disabled',false);
                // $('.price').prop('readonly',true);
            }
            $('#select_2').change(function(){
                var product_type_value = $(this).val();
                if(product_type_value == 'bundle' && ($('.company').val() != '') && ($('#edit_supplier').val() != '')) {
                     // alert('hi');
                    $('#multi_select').prop('disabled',false);

                    var supplier_value = $('#edit_supplier').val();
                    // alert(supplier_value);
                    var company_value = $('.company').val();
                    var id = $("#hidden_id").val();
                    var token = "{{csrf_token()}}";
                    var url = "{{route('product.edit',':id')}}";
                    var path = url.replace(':id',id);
                    // console.log(path);
                    $('.price').val('');
                    company_ajax(token,supplier_value,company_value,url);
                }
                else if(product_type_value == 'single'){
                    $('#multi_select').prop('disabled',true);
                    $('#multi_select').val('').trigger('change');
                    // $('.price').prop('readonly',false);
                    // $('.price').val('');
                }
            }); 
            // console.log($('.product_type').val());
            if($('.product_type').val() == 'bundle'){
                // alert('hi');
                $('#multi_select').prop('disabled',false);
            }

            $("#is_active_false").click(function(){
                $(this).prop('checked','checked');
            });
            // console.log($('.combo_product').val());
            function company_ajax(token,supplier_value,company_value,url){
                var product_value = $('.combo_product').val();
                if(supplier_value != ''){
                    $.ajax({
                        type : 'GET',
                        url : path,
                        data : {
                            'supplier_id' : supplier_value,
                            'company_name' : company_value,
                            '_token' : token    
                        },
                        success : function(data){
                            $('.combo_product').html('');
                            // console.log(product_value);
                            var toappend = '';
                            // $.each(product_value,function(i,o){
                            //     toappend +='<option>'+o+'</option>';
                            // })
                            $.each(data,function(i,o){
                                toappend +='<option>'+o+'</option>';
                            })
                            $('.combo_product').append(toappend);
                            $('.combo_product').val(product_value).trigger('change');
                        }
                    });
                }
            }
            if($('.company').val() != '' && ($('.product_type').val() == 'bundle') && ($('#edit_supplier').val() != '')){

                var supplier_value = $('#edit_supplier').val();
                // alert(supplier_value);
                var company_value = $('.company').val();
                var id = $("#hidden_id").val();
                var token = "{{csrf_token()}}";
                var url = "{{route('product.edit',':id')}}";
                var path = url.replace(':id',id);
                // console.log(path);
                company_ajax(token,supplier_value,company_value,url);
            }
            $('.company').change(function(){
                if($('#edit_supplier').val() != '' && ($('#select_2').val() == 'bundle')){
                    $('#multi_select').prop('disabled',false);
                }
                var supplier_value = $('#edit_supplier').val();
                // alert(supplier_value);
                var company_value = $('.company').val();
                var id = $("#hidden_id").val();
                var token = "{{csrf_token()}}";
                var url = "{{route('product.edit',':id')}}";
                var path = url.replace(':id',id);
                // console.log(path);
                company_ajax(token,supplier_value,company_value,url);
            });
            // function supplier_ajax(token,supplier_value,company_value,url){

            //     if(supplier_value != null){
            //         $.ajax({
            //             type : 'GET',
            //             url : path,
            //             data : {
            //                 'supplier_id' : supplier_value,
            //                 'company_name' : company_value,
            //                 '_token' : token    
            //             },
            //             success : function(data){
            //                 $('.combo_product').html('');
            //                 var toappend = '';
            //                 $.each(data,function(i,o){
            //                     toappend +='<option>'+o+'</option>';
            //                 })
            //                 $('.combo_product').append(toappend);
            //             }
            //         });
            //     }
            // }
            // if($('#edit_supplier').val() != '' && ($('.product_type').val() == 'bundle')){

            //     var supplier_value = $('#edit_supplier').val();
            //     // alert(supplier_value);
            //     var company_value = $('.company').val();
            //     var id = $("#hidden_id").val();
            //     var token = "{{csrf_token()}}";
            //     var url = "{{route('product.edit',':id')}}";
            //     var path = url.replace(':id',id);
            //     // console.log(path);
            //     supplier_ajax(token,supplier_value,company_value,url);
            // }
            $("#edit_supplier").change(function(){
                if($('.company').val() != '' && ($('#select_2').val() == 'bundle')){
                    $('#multi_select').prop('disabled',false);
                }
                var supplier_value = $('#edit_supplier').val();
                // alert(supplier_value);
                var company_value = $('.company').val();
                var id = $("#hidden_id").val();
                var token = "{{csrf_token()}}";
                var url = "{{route('product.edit',':id')}}";
                var path = url.replace(':id',id);
                // console.log(path);
                company_ajax(token,supplier_value,company_value,url);
            });
            // $(".combo_product").change(function(){
            //     var val = $(this).val();
            //     // alert($('.product_type').val());
            //     if(val != null){
            //         var id = $("#hidden_id").val();
            //         var token = "{{csrf_token()}}";
            //         var data = $(this).val();
            //         var url = "{{route('product.edit',':id')}}";
            //         var path = url.replace(':id',id);
            //         $.ajax({
            //             type : 'GET',
            //             url : path,
            //             data : {
            //                 'product_name' : data,
            //                 '_token' : token
            //             } ,
            //             success : function(data){
            //                 $('.price').val(data);
            //                 $('.price').prop('readonly',true);
            //             }
            //         })
            //     }
            //     if(val == null && $('.product_type').val() == 'bundle'){
            //         // $('.price').val(0);
            //         // $('.price').prop('readonly',true);
            //     }
            // });
        });
    </script>
@include('admin.layout.alert')
@stop