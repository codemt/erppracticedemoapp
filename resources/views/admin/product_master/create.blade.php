@extends('admin.layout.layout')
@section('style')
    <?=Html::style('backend/css/jquery.fileuploader.css')?>
    <?=Html::style('backend/css/jquery.fileuploader-theme-thumbnails.css')?>
    <?=Html::style('backend/css/bootstrap-fileupload.css')?>
@stop
@section('start_form')
    <?=Form::open(['method' => 'POST', 'route'=>'product.store', 'class' => 'm-0 form-horizontal','files'=>true])?>
@stop
@section('top_fixed_content')
<nav class="navbar navbar-static-top">
    <div class="title">
         <h4></h4>
    </div>
    <div class="pl-10">
        <button type="submit" name="save_new" value="save_new" class="btn btn-primary btn-sm disabled-btn" title="Save and add new">Save & New </button>
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
                    <h4 class="title">Create Product Master</h4>
            </div><hr>
            <div class="row">
                <div class="col-md-12">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group @if($errors->has('company_id')) has-error @endif">
                                <div class="col-md-12">Select Company<sup class="text-danger">*</sup></div>
                                <div class="col-md-12">
                                    <?= Form::select('company_id[]',$company_list, old('company_id[]'),array('class' => 'form-control select2_2 company','data-placeholder'=>'Select Company','multiple'=>true,'id'=>'company_id')) ?>
                                    <span id="select_2_error" class="help-inline text-danger"><?=$errors->first('company_id')?></span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group @if($errors->has('supplier_id')) has-error @endif">
                                <div class="col-md-12">Select Manufacturer<sup class="text-danger">*</sup></div>
                                <div class="col-md-12">
                                    <?= Form::select('supplier_id',$supplier_list, old('supplier_id'),array('class' => 'form-control select_2 supplier','placeholder'=>'Select Manufacturer','id' => 'supplier')) ?>
                                    <span id="select_2_error" class="help-inline text-danger"><?=$errors->first('supplier_id')?></span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group @if($errors->has('product_type')) has-error @endif">
                                <div class="col-md-12">Select Product Type<sup class="text-danger">*</sup></div>
                                <div class="col-md-12">
                                    <?= Form::select('product_type',Config::get('product_type'), old('product_type'),array('class' => 'form-control select_2 product_type','id'=>'select_2','placeholder'=>'Select Product Type')) ?>
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
                                    <?=Form::select('combo_product[]',$product_list,old('combo_product[]'), ['class' => 'form-control select2 combo_product ','data-placeholder'=>'Select Product','multiple'=>true]);?>
                                    <strong><small class="form-text text-muted">Company name , Manufacturer name and Product type(bundle) must required for selecting products.
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
                                    <?=Form::text('model_no', old('model_no'), ['class' => 'form-control', 'placeholder' => 'Model Number','maxlength'=>'100']);?>
                                    <span id="text_box_error" class="help-inline text-danger"><?=$errors->first('model_no')?></span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group @if($errors->has('name_description')) {{ 'has-error' }} @endif">
                                <div class="col-md-12">Name & Description<sup class="text-danger">*</sup></div>
                                <div class="col-md-12">
                                    <?=Form::text('name_description', old('name_description'), ['class' => 'form-control', 'placeholder' => 'Name & Description']);?>
                                    <span id="text_box_error" class="help-inline text-danger"><?=$errors->first('name_description')?></span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group @if($errors->has('price')) {{ 'has-error' }} @endif">
                                <div class="col-md-12">Price<sup class="text-danger">*</sup></div>
                                <div class="col-md-12">
                                    <?=Form::text('price', old('price'), ['class' => 'form-control number_only', 'placeholder' => 'Price']);?>
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
                                    <?=Form::text('unit', old('unit'), ['class' => 'form-control number_only', 'placeholder' => 'Units in  mtr/lott/no']);?>
                                    <span id="text_box_error" class="help-inline text-danger"><?=$errors->first('unit')?></span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group @if($errors->has('max_discount')) {{ 'has-error' }} @endif">
                                <div class="col-md-12">Maximum Discount Applied<sup class="text-danger">*</sup></div>
                                <div class="col-md-12">
                                    <?=Form::text('max_discount', old('max_discount'), ['class' => 'form-control number_only', 'placeholder' => 'Maximum Discount Applied']);?>
                                    <span id="text_box_error" class="help-inline text-danger"><?=$errors->first('max_discount')?></span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group @if($errors->has('tax')) has-error @endif">
                                <div class="col-md-12">Tax<sup class="text-danger">*</sup></div>
                                <div class="col-md-12">
                                    <?= Form::select('tax',[''=>'Select Tax'] + Config::get('tax'), old('tax'),array('class' => 'form-control select_2 tax','placeholder'=>'Select Tax')) ?>
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
                                            <img src="<?=asset('backend/images/no_image.png', IS_SECURE)?>" alt="" />
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
                                            <?=Form::text('hsn_code', old('hsn_code'), ['class' => 'form-control', 'placeholder' => 'HSN Code','maxlength'=>'9']);?>
                                            <span id="text_box_error" class="help-inline text-danger"><?=$errors->first('hsn_code')?></span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group @if($errors->has('weight')) {{ 'has-error' }} @endif">
                                        <div class="col-md-12">Weight</div>
                                        <div class="col-md-12">
                                            <?=Form::text('weight', old('weight'), ['class' => 'form-control number_only', 'placeholder' => 'Weight']);?>
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
                                            <?=Form::text('qty', old('qty'), ['class' => 'form-control qty_only', 'placeholder' => 'Qty','maxlength'=>'10']);?>
                                            <span id="text_box_error" class="help-inline text-danger"><?=$errors->first('qty')?></span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group @if($errors->has('min_qty')) {{ 'has-error' }} @endif">
                                        <div class="col-md-12">Minimum QTY to maintain<sup class="text-danger">*</sup></div>
                                        <div class="col-md-12">
                                            <?=Form::text('min_qty', old('min_qty'), ['class' => 'form-control qty_only', 'placeholder' => 'Minimum QTY to maintain','maxlength'=>'100']);?>
                                            <span id="text_box_error" class="help-inline text-danger"><?=$errors->first('min_qty')?></span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group @if($errors->has('product_status')) {{ 'has-error' }} @endif">
                                        <div class="col-md-12">Enable/Disable Product</div>
                                        <div class="col-md-12">
                                            <div class="animated-radio-button pull-left mr-10">
                                                <label class="control-label" for="is_active_true">
                                                    <input type="radio" name="product_status" value="1" {{old('product_status') == '1' ? 'checked' : ''}} id="is_active_true" checked="checked">
                                                    <span class="label-text"></span> Enable
                                                </label>
                                            </div>
                                            <div class="animated-radio-button pull-left">
                                                <label class="control-label" for="is_active_false">
                                                    <input type="radio" name="product_status" value="0" {{old('product_status') == '0' ? 'checked' : ''}} id="is_active_false">
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
    <button type="submit" name="save_new" value="save_new" class="btn btn-primary btn-sm disabled-btn" title="Save and add new user">Save & New </button>
        
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
<?= Html::script('https://cdn.jsdelivr.net/npm/jquery.session@1.0.0/jquery.session.min.js',[],IS_SECURE) ?>

    <script type="text/javascript">
        $(document).ready(function(){
            
            $('.combo_product').prop('disabled',true);
            // $('.company').select2({
            //     placeholder : "Select Company",
            // });
            $('.supplier').select2({
                placeholder : "Select Manufacturer",
            });
            $('.product_type').select2({
                placeholder : "Select Product Type",
            });
             var tag1 = $('.combo_product').select2({
                placeholder : "Select Ptoduct"
            });
            @if(old('combo_product'))
                var old_tag1 = {!! json_encode(old('combo_product')) !!};        
                $.each(old_tag1,function(k,v){        
                    $(".combo_product").append($('<option>', {value: v, text: v}));    
                });   
                $('.combo_product').val(old_tag1).trigger('change');         
            @endif 
            $('.tax').select2({
                placeholder : "Select Tax",
            });
            if($('.product_type').val() == 'single'){
                $('.combo_product').prop('disabled',true);
                $('.combo_product').val('').trigger('change');
                // $('.price').prop('readonly',false);
                // $('.price').val('');
            }
            if($('.product_type').val() == 'bundle'){
                $('.combo_product').prop('disabled',false);
                // $('.price').prop('readonly',true);
            }
            $('#select_2').change(function(){
                var product_type_value = $(this).val();
                if(product_type_value == 'bundle' && ($('.company').val() != '') && ($('#supplier').val() != '')) {
                     // alert('hi');
                    $('.combo_product').prop('disabled',false);
                }
                else if(product_type_value == 'single'){
                    $('.combo_product').prop('disabled',true);
                    $('.combo_product').val('').trigger('change');
                    // $('.price').prop('readonly',false);
                    // $('.price').val('');
                }
            }); 
            // console.log($('.company').val());
            if($('#select_2').val() == 'bundle'){
                $('.combo_product').prop('disabled',false);
            }
            $("#is_active_false").click(function(){
                $(this).prop('checked','checked');
            });
            function company_ajax(token,supplier_value,company_value,url){
                if(supplier_value != ''){
                    $.ajax({
                        type : 'GET',
                        url : url,
                        data : {
                            'supplier_id' : supplier_value,
                            'company_name' : company_value,
                            '_token' : token    
                        },
                        success : function(data){
                            $('.combo_product').html('');
                            var toappend = '';
                            $.each(data,function(i,o){
                                toappend +='<option>'+o+'</option>';
                            })
                            $('.combo_product').append(toappend);
                            // var combo_product = $(data).html();
                            // $('.combo_product').html(combo_product);
                            // $('.combo_product').select2({placeholder:'Select Product'});
                            // $('.combo_product')
                        }
                    });
                }
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
            if($('.company').val() != '' && ($('#supplier').val() != '')){

                var supplier_value = $('#supplier').val();
                // console.log(supplier_value);
                var company_value = $('.company').val();
                var token = "{{csrf_token()}}";
                var url = "<?= route('product.create')?>";
                supplier_list(token,company_value,url);
                company_ajax(token,supplier_value,company_value,url);
            }
            $('.company').change(function(){
                if($('#supplier').val() != '' && ($('#select_2').val() == 'bundle')){
                    $('.combo_product').prop('disabled',false);
                }

                var supplier_value = $('#supplier').val();
                // console.log(supplier_value);
                var company_value = $('.company').val();
                var token = "{{csrf_token()}}";
                var url = "<?= route('product.create')?>";
                company_ajax(token,supplier_value,company_value,url);
            });
            // function supplier_ajax(token,supplier_value,company_value,url){
            //     if(supplier_value != null){
            //         $.ajax({
            //             type : 'GET',
            //             url : url,
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
            //                 // var combo_product = $(data).html();
            //                 // $('.combo_product').html(combo_product);
            //                 // $('.combo_product').select2({placeholder:'Select Product'});
            //                 // $('.combo_product')
            //             }
            //         });
            //     }
            // }
            // if($('#supplier').val() != ''){
            //     var supplier_value = $('#supplier').val();
            //     // alert(supplier_value);
            //     var company_value = $('.company').val();
            //     // alert(company_value);
            //     var token = "{{csrf_token()}}";
            //     var url = "<?= route('product.create')?>";
            //     supplier_ajax(token,supplier_value,company_value,url);
            // }
            // $('#supplier').val($('#supplier').val()).trigger('change');
            $("#supplier").change(function(){
                if($('.company').val() != '' && ($('#select_2').val() == 'bundle')){
                    $('.combo_product').prop('disabled',false);
                }
                
                var supplier_value = $('#supplier').val();
                // alert(supplier_value);
                var company_value = $('.company').val();
                // alert(company_value);
                var token = "{{csrf_token()}}";
                var url = "<?= route('product.create')?>";
                company_ajax(token,supplier_value,company_value,url);
                
            });
            // $(".combo_product").change(function(){
            //     var val = $(this).val();
            //     var company_value = $('.company').val();
            //     var supplier_value = $('#supplier').val();
            //     var product_type = $('.product_type').val();
            //     if(val != null){
            //         var token = "{{csrf_token()}}";
            //         var data = $(this).val();
            //         var url = "<?= route('product.create')?>";
            //         $.ajax({
            //             type : 'GET',
            //             url : url,
            //             data : {
            //                 'product_name' : data,
            //                 '_token' : token
            //             } ,
            //             success : function(data){
            //                 $('.price').val(data);
            //                 $('.price').prop('readonly',true);
            //             }
            //         }); 
            //     }
            //     if(val == null && $('.product_type').val() == 'bundle'){
            //         // console.log('hi');
            //         // $('.price').val(0);
            //     
                // $('.price').prop('readonly',true);
            //     }
            // });
        });
    </script>
    <script type="text/javascript">
        

            var tag = $('#company_id').select2({
                placeholder : "Select Company"
            });
            @if(old('company_id'))
                var old_tag = {!! json_encode(old('company_id')) !!};        
                $('#company_id').val(old_tag).trigger('change');         
                $.each(old_tag,function(k,v){        
                    $("#company_id").append($('<option>', {value: v, text: v}));    
                });  
                // tag.val(old_tag).trigger('change'); 
            @endif
    </script>
    <?=Html::script('backend/js/form.demo.min.js', [], IS_SECURE)?>
@include('admin.layout.alert')
@stop