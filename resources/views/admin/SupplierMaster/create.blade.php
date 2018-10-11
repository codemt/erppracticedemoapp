@extends('admin.layout.layout')
@section('style')
@stop
@section('start_form')
    <?=Form::open(['method' => 'post','route' => 'manufacturer.store', 'class' => 'm-0 form-horizontal'])?>
@stop
@section('top_fixed_content')
<nav class="navbar navbar-static-top">
    <div class="title">
         <h4></h4>
    </div>
    <div class="pl-10">
        <button type="submit" name="save_button" value="save_new" class="btn btn-primary btn-sm disabled-btn" title="Save and add new">Save & New </button>
        <button type="submit" name="save_button" value="save_exit" class="btn btn-primary btn-sm disabled-btn" title="Save and exit">Save & exit </button>
        <a href="<?= route('manufacturer.index') ?>" class="btn btn-default btn-sm" title="Back to users Page">Cancel</a>
    </div>
</nav>
@stop
@section('content')
<div class="row">
    <div class="col-md-12 mb-30">
        <div class="card">
            <div class="card-title-w-btn">
                    <h4 class="title">Create Manufacturer Master</h4>
            </div><hr>
            <div class="row">
                <div class="col-md-12">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group @if($errors->has('supplier_name')) has-error @endif">
                                <div class="col-md-12">Manufacturer Name<sup class="text-danger">*</sup></div>
                                <div class="col-md-12">
                                    <?= Form::text('supplier_name', old('supplier_name'),array('class' => 'form-control','placeholder'=>'Manufacturer Name','maxlength' => 100)) ?>
                                    <span id="select_2_error" class="help-inline text-danger"><?=$errors->first('supplier_name')?></span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group @if($errors->has('spoc_name')) has-error @endif">
                                <div class="col-md-12">SPOC Name<sup class="text-danger">*</sup></div>
                                <div class="col-md-12">
                                    <?= Form::text('spoc_name', old('spoc_name'),array('class' => 'form-control char_only select_2','placeholder'=>'SPOC Name','maxlength' => 100)) ?>
                                    <span id="select_2_error" class="help-inline text-danger"><?=$errors->first('spoc_name')?></span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group @if($errors->has('spoc_email')) has-error @endif">
                                <div class="col-md-12">SPOC Email<sup class="text-danger">*</sup></div>
                                <div class="col-md-12">
                                    <?= Form::select('spoc_email[]',[], old('spoc_email[]'),array('class' => 'form-control select2_2 spoc_email','id'=>'multi_select_email','multiple' => true)) ?>
                                    <span id="select_2_error" class="help-inline text-danger errorMsg"><?=$errors->first('spoc_email')?></span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>                        
                <div class="col-md-12">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group @if($errors->has('spoc_phone')) has-error @endif">
                                <div class="col-md-12">SPOC Phone<sup class="text-danger">*</sup></div>
                                <div class="col-md-12">
                                    <?= Form::select('spoc_phone[]',[], old('spoc_phone[]'),array('class' => 'form-control select2_2 number_only spoc_phone','id' => 'multi_select','multiple' => true)) ?>
                                    <span id="select_2_error" class="help-inline text-danger"><?=$errors->first('spoc_phone')?></span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group @if($errors->has('gst_no')) {{ 'has-error' }} @endif">
                                <div class="col-md-12">GST/UIN<sup class="text-danger">*</sup></div>
                                <div class="col-md-12">
                                    <?=Form::text('gst_no', old('gst_no'), ['class' => 'form-control', 'placeholder' => 'GST/UIN','maxlength'=>15]);?>
                                    <span id="text_box_error" class="help-inline text-danger"><?=$errors->first('gst_no')?></span>
                                </div>
                            </div>
                        </div>                    
                        <div class="col-md-4">
                            <div class="form-group @if($errors->has('pan_no')) {{ 'has-error' }} @endif">
                                <div class="col-md-12">Pan No<sup class="text-danger">*</sup></div>
                                <div class="col-md-12">
                                    <?=Form::text('pan_no', old('pan_no'), ['class' => 'form-control', 'placeholder' => 'Pan No','maxlength'=>12]);?>
                                    <span id="text_box_error" class="help-inline text-danger"><?=$errors->first('pan_no')?></span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-12">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group @if($errors->has('ifsc_code')) {{ 'has-error' }} @endif">
                                <div class="col-md-12">IFSC Code<sup class="text-danger">*</sup></div>
                                <div class="col-md-12">
                                    <?=Form::text('ifsc_code', old('ifsc_code'), ['class' => 'form-control', 'placeholder' => 'IFSC Code','maxlength'=>11]);?>
                                    <span id="text_box_error" class="help-inline text-danger"><?=$errors->first('ifsc_code')?></span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group @if($errors->has('branch')) {{ 'has-error' }} @endif">
                                <div class="col-md-12">Branch<sup class="text-danger">*</sup></div>
                                <div class="col-md-12">
                                    <?=Form::text('branch', old('branch'), ['class' => 'form-control char_only', 'placeholder' => 'Branch']);?>
                                    <span id="text_box_error" class="help-inline text-danger"><?=$errors->first('branch')?></span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group @if($errors->has('bankname')) {{ 'has-error' }} @endif">
                                <div class="col-md-12">Bank Name<sup class="text-danger">*</sup></div>
                                <div class="col-md-12">
                                    <?=Form::text('bankname', old('bankname'), ['class' => 'form-control char_only', 'placeholder' => 'Bank Name']);?>
                                    <span id="text_box_error" class="help-inline text-danger"><?=$errors->first('bankname')?></span>
                                </div>
                            </div>
                        </div>
                    </div>                    
                </div>
                <div class="col-md-12">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group @if($errors->has('ac_number')) {{ 'has-error' }} @endif">
                                <div class="col-md-12">AC Number<sup class="text-danger">*</sup></div>
                                <div class="col-md-12">
                                    <?=Form::text('ac_number', old('ac_number'), ['class' => 'form-control', 'placeholder' => 'AC Number','maxlength'=>12]);?>
                                    <span id="text_box_error" class="help-inline text-danger"><?=$errors->first('ac_number')?></span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group @if($errors->has('company_id')) has-error @endif">
                                <div class="col-md-12">Select Company<sup class="text-danger">*</sup></div>
                                <div class="col-md-12">
                                    <?= Form::select('company_id[]',$company_list, old('company_id[]'),array('class' => 'form-control select2_2 company','data-placeholder'=>'Select Company','multiple'=>true,'id'=>'company_id')) ?>
                                    <span id="select_2_error" class="help-inline text-danger"><?=$errors->first('company_id')?></span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div> 
                <div class="col-md-12">
                    <div class="form-group row">
                        <div class="col-md-12 table-responsive">
                            <table class="table m-0 v-top">
                                <thead>
                                    <tr>
                                        <th style="border:none">Title<sup class="text-danger">*</sup></th>
                                        <th style="border:none">Address<sup class="text-danger">*</sup></th>
                                        <th style="border:none">Country<sup class="text-danger">*</sup></th>
                                        <th style="border:none">State<sup class="text-danger">*</sup></th>
                                        <th style="border:none">City<sup class="text-danger">*</sup></th>
                                        <th style="border:none">Pincode<sup class="text-danger">*</sup></th>
                                        <th style="border:none"></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr id="shipping">
                                        <td class="col-md-2" style="border:none" valign="top">
                                            <?= Form::text('title',old('title'),['class' => 'form-control select2 title','id'=>'title','style'=>'width:150px','placeholder' => 'Title']); ?>
                                            <span id="title_error" class="help-inline text-danger"><?= $errors->first('shipping.shipping.title') ?></span>
                                        </td>
                                        <td class="col-md-2" style="border:none" valign="top">
                                            <?= Form::text('address',old('address'),['class' => 'form-control select2 address','id'=>'address','style'=>'width:170px','placeholder' => 'Address','rows'=>1]); ?>
                                            <span id="address_error" class="help-inline text-danger"><?= $errors->first('shipping.shipping.address') ?></span>
                                        </td>
                                        <td class="col-md-2" style="border:none;padding-left: 0" valign="top">
                                            <?= Form::select('country_id',[''=>'Select Country']+$country,old('country_id'), ['class' => 'form-control select2 country_id','id'=>'country_id','style'=>'width:150px']); ?>
                                            <span id="country_id_error" class="help-inline text-danger"><?= $errors->first('shipping.shipping.country_id') ?></span>
                                        </td>
                                        <td class="col-md-2" style="border:none" valign="top">
                                            <?= Form::select('state_id',[''=>'Select State'],old('state_id'), ['class' => 'form-control select2 state_id','id'=>'state_id','style'=>'width:150px']); ?>
                                            <span id="state_id_error" class="help-inline text-danger"><?= $errors->first('shipping.shipping.state_id') ?></span>
                                        </td>
                                        <td class="col-md-2" style="border:none" valign="top">
                                            <?= Form::select('city_id',[''=> 'Select City'],old('city_id'), ['class' => 'form-control select2 city_id','id'=>'city_id','style'=>'width:150px']); ?>
                                            <span id="city_id_error" class="help-inline text-danger"><?= $errors->first('shipping.shipping.city_id') ?></span>
                                        </td>
                                        <td class="col-md-1" style="border:none" valign="top">
                                               <?= Form::text('pincode',old('pincode'),['class' => 'form-control number_only select2 pincode','id'=>'pincode','style'=>'width:70px','placeholder' => 'Pincode','maxlength'=>6]); ?>
                                            <span id="pincode_error" class="help-inline text-danger"><?= $errors->first('shipping.shipping.pincode') ?></span>
                                        </td>
                                        <td class="col-md-1" style="border:none" valign="top">
                                            <a id="shipping_remove" class="pt-10 pull-left btn-remove"><i class="fa fa-minus-circle fa-small pull-left"></i></a>
                                            <a id="shipping_add" class="pt-10 pull-left btn-add" ><i class="fa fa-plus-circle fa-small pull-left" ></i></a>
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
    <button type="submit" name="save_button" value="save_new" class="btn btn-primary btn-sm disabled-btn new" title="Save and add new user">Save & New </button>
        
    <button type="submit" name="save_button" value="save_exit" class="btn btn-primary btn-sm disabled-btn new" title="Save & exit">Save & exit</button>
    <a href="<?= route('manufacturer.index') ?>" class="btn btn-default btn-sm" title="Back to user Page">Cancel</a>
</div>
@stop

@section('end_form')
<?=Form::close()?>
@stop
@section('script')
<?= Html::script('backend/js/dynamicform.js',[],IS_SECURE) ?>
<?= Html::script('backend/js/jquery.form.min.js',[],IS_SECURE) ?>
<?= Html::script('backend/js/select2.full.min.js',[],IS_SECURE) ?>


<script type="text/javascript">


    $(".number_only").keypress(function(h){if(8!=h.which&&0!=h.which&& 32!=h.which&&(h.which<48||h.which>57))return!1});

    $(".char_only").keypress(function (e) {
        if (e.which != 8 && e.which != 0 && e.which != 32 && (e.which < 97 || e.which > 122)&& (e.which < 65 || e.which > 90))return !1}); 


        function getCountry(country){
            var result = '';

            $.ajax({
                url: "<?= URL::route('suppliers.getstate') ?>",
                type: 'post',
                dataType: 'json',
                async : false,
                data: {
                    country: country,
                    _token:'<?= csrf_token()?>'
                },
                beforeSend: function() {
                    $('div.overlay').show();
                },
                complete: function() {
                    $('div.overlay').hide();
                },
                success: function(resp) {
                    result = resp.data;
                }, 
            });

            return result;
        }
        $('body').on('change','.country_id',function (){
        // var select_html  = $(this).parent('td').parent('tr').find('.state_id');
            var select_html  = $(this).parent('td').parent('tr').children('td').find('.state_id');
            var state_select_id = select_html.attr('id');

            var country = $(this).val();
            resp = getCountry(country);
            select_html.html('').select2({'data':resp}).val();
            
        });
        $('body').on('change','.state_id',function (){
            var select_html  = $(this).parent('td').parent('tr').children('td').find('.city_id');
            var state_city_id = select_html.attr('id');

            var state = $(this).val();
            $.ajax({
                url: "<?= URL::route('suppliers.getcity') ?>",
                type: 'post',
                dataType: 'json',
                async : false,
                data: {
                    state: state,
                    _token:'<?= csrf_token()?>'
                },
                beforeSend: function() {
                    $('#spin').show();
                },
                complete: function() {
                    $('#spin').hide();
                },
                success: function(resp) {
                    select_html.html('').select2({'data':resp.data});
                },
            });
        });

    var supplier_shipping_detail =  $("#shipping").dynamicForm("#shipping_add", "#shipping_remove", {
        limit: 10,
        normalizeFullForm : false,
    });

    old_data = <?= json_encode(old('shipping.shipping')) ?>;
    // console.log(old_data);
    supplier_shipping_detail.inject(old_data);

    var select_list = $('tbody tr');
    // console.log(old_data);
    if(old_data != null){
        // console.log('1');
        if (old_data.length > 0) {
            $.each(select_list,function(k,v){
                var country = old_data[k]['country_id'];
                // console.log(country);
                var country_test = getCountry(country);
                // console.log(country_test);
                $(v).find('.state_id').html('').select2({'data':country_test}).select2('val',old_data[k]['state_id']);
                $(v).find('.city_id').select2('val',old_data[k]['city_id']);
            });
        }
    }
     @if($errors)
        var detail_Errors = <?= json_encode($errors->toArray()) ?>;
        
        $.each(detail_Errors, function(id,msg){
            var id_arr = id.split('.');
            if (id_arr[3] == 'title') {
                $('#'+id_arr[3]+id_arr[id_arr.length-2]).closest('td').find('span').text(msg[0]);
            }
            if (id_arr[3] == 'address') {
                $('#'+id_arr[3]+id_arr[id_arr.length-2]).closest('td').find('span').text(msg[0]);
            }
            if (id_arr[3] == 'country_id') {
                $('#'+id_arr[3]+id_arr[id_arr.length-2]).closest('td').find('span').text(msg[0]);
            }
            if (id_arr[3] == 'state_id') {
                $('#'+id_arr[3]+id_arr[id_arr.length-2]).closest('td').find('span').text(msg[0]);
            }
            if (id_arr[3] == 'city_id') {
                $('#'+id_arr[3]+id_arr[id_arr.length-2]).closest('td').find('span').text(msg[0]);
            }
            if (id_arr[3] == 'pincode') {
                $('#'+id_arr[3]+id_arr[id_arr.length-2]).closest('td').find('span').text(msg[0]);
            }
        });
    @endif
    </script>

<!-- script for select tag -->
<script type="text/javascript">
    $('.country_id').select2({
        placeholder : "Select Country"
    });
    $('.state_id').select2({
        placeholder : "Select State"
    });
    $('.city_id').select2({
        placeholder : "Select City"
    });
    
    var tag = $('#multi_select').select2({
        placeholder : "Spoc Phone"
    
    });

    var tag1 = $('#multi_select_email').select2({
        placeholder : "Spoc Email"
    
    });
    
    @if(old('spoc_phone'))
    
        var old_tag = {!! json_encode(old('spoc_phone')) !!};        
        $('spoc_phone').val(old_tag).trigger('change');         
        $.each(old_tag,function(k,v){        
            $("#multi_select").append($('<option>', {value: v, text: v}));    
        }); 
        tag.val(old_tag).trigger('change');    
    @endif
    @if(old('spoc_email'))
    
        var old_tag1 = {!! json_encode(old('spoc_email')) !!};        
        $('spoc_email').val(old_tag1).trigger('change');         
        $.each(old_tag1,function(k,v){        
            $("#multi_select_email").append($('<option>', {value: v, text: v}));    
        }); 
        tag1.val(old_tag1).trigger('change');    
    @endif
</script>
<script type="text/javascript">
    var tag = $('#company_id').select2({
        placeholder : "Select Company"
    });
</script>
<?=Html::script('backend/js/form.demo.min.js', [], IS_SECURE)?> 
@include('admin.layout.alert')
@stop