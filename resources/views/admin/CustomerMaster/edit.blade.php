@extends('admin.layout.layout')
@section('style')
@stop
@section('start_form')
    <?=Form::model($customerupdate_data,['route' => ['customer.update',$id],'method' => 'patch', 'class' => 'm-0 form-horizontal'])?>
@stop
@section('top_fixed_content')
<nav class="navbar navbar-static-top">
    <div class="title">
         <h4></h4>
    </div>
    <div class="pl-10">
        <button type="submit" name="save_button" value="save_new" class="btn btn-primary btn-sm disabled-btn" title="Save and add new">Save</button>
        <button type="submit" name="save_button" value="save_exit" class="btn btn-primary btn-sm disabled-btn" title="Save and exit">Save & exit </button>
        <a href="<?= route('customer.index') ?>" class="btn btn-default btn-sm" title="Back to users Page">Cancel</a>
    </div>
</nav>
@stop
@section('content')
<div class="row">
    <div class="col-md-12 mb-30">
        <div class="card">
            <div class="card-title-w-btn">
                    <h4 class="title">Edit Customer Master</h4>
            </div><hr>
            <div class="card-sub-title">
                <h5>Customer Detail</h5>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group @if($errors->has('name')) has-error @endif">
                                <div class="col-md-12">Name<sup class="text-danger">*</sup></div>
                                <div class="col-md-12">
                                    <?= Form::text('name', old('name'),array('class' => 'form-control','placeholder'=>'Name','maxlength' => 100)) ?>
                                    <span id="select_2_error" class="help-inline text-danger errorMsg"><?=$errors->first('name')?></span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group @if($errors->has('person_name')) has-error @endif">
                                <div class="col-md-12">Contact Person Name<sup class="text-danger">*</sup></div>
                                <div class="col-md-12">
                                    <?= Form::text('person_name', old('person_name'),array('class' => 'form-control char_only select_2','placeholder'=>'Contact Person Name','maxlength' => 100)) ?>
                                    <span id="select_2_error" class="help-inline text-danger errorMsg"><?=$errors->first('person_name')?></span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group @if($errors->has('person_email')) has-error @endif">
                                <div class="col-md-12">Contact Person Email<sup class="text-danger">*</sup></div>
                                <div class="col-md-12">
                                    <?= Form::select('person_email[]',[], old('person_email[]'),array('class' => 'form-control select2_2 person_email','id'=>'multi_select_email','multiple' => true)) ?>
                                    <span id="select_2_error" class="help-inline text-danger errorMsg"><?=$errors->first('person_email')?></span>
                                </div>
                            </div>
                        </div>                        
                    </div>
                </div>                        
                <div class="col-md-12">
                    <div class="row">                        
                        <div class="col-md-4">
                            <div class="form-group @if($errors->has('person_phone')) has-error @endif">
                                <div class="col-md-12">Contact Person Phone<sup class="text-danger">*</sup></div>
                                <div class="col-md-12">
                                    <?= Form::select('person_phone[]',[], old('person_phone[]'),array('class' => 'form-control number_only select2_2 person_phone','id' => 'multi_select','multiple' => true)) ?>
                                    <span id="select_2_error" class="help-inline text-danger errorMsg"><?=$errors->first('person_phone')?></span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group @if($errors->has('gst_no')) {{ 'has-error' }} @endif">
                                <div class="col-md-12">GST/UIN<sup class="text-danger">*</sup></div>
                                <div class="col-md-12">
                                    <?=Form::text('gst_no', old('gst_no'), ['class' => 'form-control', 'placeholder' => 'GST/UIN','maxlength'=>15]);?>
                                    <span id="text_box_error" class="help-inline text-danger errorMsg"><?=$errors->first('gst_no')?></span>
                                </div>
                            </div>
                        </div> 
                        <div class="col-md-4">
                            <div class="form-group @if($errors->has('pan_no')) {{ 'has-error' }} @endif">
                                <div class="col-md-12">Pan No<sup class="text-danger">*</sup></div>
                                <div class="col-md-12">
                                    <?=Form::text('pan_no', old('pan_no'), ['class' => 'form-control', 'placeholder' => 'Pan No','maxlength'=>10]);?>
                                    <span id="text_box_error" class="help-inline text-danger errorMsg"><?=$errors->first('pan_no')?></span>
                                </div>
                            </div>
                        </div>                        
                    </div>
                </div>                      
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
                    </div>
                </div>
            </div>
        </div>        
        <div class="card">
            <div class="card-sub-title">
                <h5>Address Detail</h5>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <div class="form-group row">
                        <div class="col-md-12 table-responsive">
                            <table class="table m-0 v-top">
                                <thead>
                                    <tr>
                                        <th style="border:none">Title<sup class="text-danger">*</sup></th>
                                        <th style="border:none">Area<sup class="text-danger">*</sup></th>
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
                                            <?= Form::text('title',old('title'),['class' => 'form-control char_only select2 title','id'=>'title','style'=>'width:120px','placeholder' => 'Title']); ?>
                                            <span id="title_error" class="help-inline text-danger"><?= $errors->first('shipping.shipping.title') ?></span>
                                        </td>
                                        <td class="col-md-2" style="border:none" valign="top">
                                            <?= Form::text('area',old('area'),['class' => 'form-control char_only select2 area','id'=>'area','style'=>'width:120px','placeholder' => 'Area',]); ?>
                                            <span id="area_error" class="help-inline text-danger"><?= $errors->first('shipping.shipping.area') ?></span>
                                        </td>
                                        <td class="col-md-2" style="border:none" valign="top">
                                            <?= Form::text('address',old('address'),['class' => 'form-control address','id'=>'address','style'=>'width:120px','placeholder' => 'Address','rows'=>1]); ?>
                                            <span id="address_error" class="help-inline text-danger"><?= $errors->first('shipping.shipping.address') ?></span>
                                        </td>
                                        <td class="col-md-2" style="border:none;padding-left: 0" valign="top">
                                            <?= Form::select('country_id',[''=>'Select Country']+$country,old('country_id'), ['class' => 'form-control select2 country_id','id'=>'country_id','style'=>'width:120px']); ?>
                                            <span id="country_id_error" class="help-inline text-danger"><?= $errors->first('shipping.shipping.country_id') ?></span>
                                        </td>
                                        <td class="col-md-1" style="border:none" valign="top">
                                            <?= Form::select('state_id',[''=>'Select State'],old('state_id'), ['class' => 'form-control select2 state_id','id'=>'state_id','style'=>'width:120px']); ?>
                                            <span id="state_id_error" class="help-inline text-danger"><?= $errors->first('shipping.shipping.state_id') ?></span>
                                        </td>
                                        <td class="col-md-1" style="border:none" valign="top">
                                            <?= Form::select('city_id',[''=> 'Select City'],old('city_id'), ['class' => 'form-control select2 city_id','id'=>'city_id','style'=>'width:120px']); ?>
                                            <span id="city_id_error" class="help-inline text-danger"><?= $errors->first('shipping.shipping.city_id') ?></span>
                                        </td>
                                        <td class="col-md-1" style="border:none" valign="top">
                                               <?= Form::text('pincode',old('pincode'),['class' => 'form-control select2 number_only pincode','id'=>'pincode','style'=>'width:70px','placeholder' => 'Pincode','maxlength'=>6]); ?>
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
                <button type="submit" name="save_button" value="save_new" class="btn btn-primary btn-sm disabled-btn" title="Save and add new user">Save</button>

                <button type="submit" name="save_button" value="save_exit" class="btn btn-primary btn-sm disabled-btn" title="Save & exit">Save & exit</button>
                <a href="<?= route('customer.index') ?>" class="btn btn-default btn-sm" title="Back to user Page">Cancel</a>
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
        if (e.which != 8 && e.which != 0 && e.which != 32 && (e.which < 97 || e.which > 122)&& (e.which < 65 || e.which > 90))return !1           
    });

    var country_id = $('.country_id').val();

    function getCountry(country){
        var result = '';
        $.ajax({
            url: "<?= URL::route('customer.getstate') ?>",
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
            //console.log(result);
            }, 
        });

        return result;
    }

    function getState(state){
        var result = '';
        $.ajax({
            url: "<?= URL::route('customer.getcity') ?>",
            type: 'post',
            dataType: 'json',
            async : false,
            data: {
                state: state,
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
            //console.log(result);
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
        // var select_html  = $(this).parent('td').parent('tr').find('.state_id');
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

$(document).ready(function(){
        var country_val = $('.country_id');
        // console.log(country_val.val());
        if(country_val.val() != null)
        {
            var select_html  = $(this).parent('td').parent('tr').find('.state_id');
            var country = country_val.val();
            resp = getCountry(country);

            var customer_data_info = {!! json_encode($customer_data_info) !!};
            
            $(customer_data_info).each(function(customer_data_key,customer_data_value){
                $(resp).each(function(k,v){
                    // var t = "<option selected value='"+v.id+"'>"+ v.text+"</option>";
                    if(customer_data_value.state_id == v.id){
                        $('#state_id'+customer_data_key).append("<option selected value='"+v.id+"'>"+ v.text+"</option>");

                        respcity = getState(v.id);
                        $(respcity).each(function(kc,vc){
                            // console.log(vc.id);
                            if(customer_data_value.city_id == vc.id){
                                $('#city_id'+customer_data_key).append("<option selected value='"+vc.id+"'>"+ vc.text+"</option>");
                            }else{
                                // console.log('hi');
                                $('#city_id'+customer_data_key ).append($('<option>', {value: vc.id, text: vc.text}));
                            }
                        });

                    }else{
                        $('#state_id'+customer_data_key).append($('<option>', {value: v.id, text: v.text}));
                    }
                });
            });
            var city_data = $('.city_id').html();
            var state_data = $('.state_id').html();
        }
    });

    var customer_shipping_detail =  $("#shipping").dynamicForm("#shipping_add", "#shipping_remove", {
        limit: 5,
        normalizeFullForm : false,
    });
    old_data = <?= json_encode(old('shipping.shipping',$customer_data_info)) ?>;

    customer_shipping_detail.inject(old_data);

    var select_list = $('tbody tr');

    $.each(select_list,function(k,v){
        var country = old_data[k]['country_id'];
        // console.log(country);
        var country_test = getCountry(country);
        // console.log(country_test);
        $(v).find('.state_id').html('').select2({'data':country_test}).select2('val',old_data[k]['state_id']);
        $(v).find('.city_id').select2('val',old_data[k]['city_id']);
    });
    @if($errors)
    var detail_Errors = <?= json_encode($errors->toArray()) ?>;

        $.each(detail_Errors, function(id,msg){
            var id_arr = id.split('.');
            if (id_arr[3] == 'title') {
                $('#'+id_arr[3]+id_arr[id_arr.length-2]).closest('td').find('span').text(msg[0]);
            }
            if (id_arr[3] == 'area') {
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
    var tag1 = $('#multi_select').select2({
        placeholder : "Contact Person Phone"
    });

    var tag = $('#multi_select_email').select2({
        placeholder : "Contact Person Email"

    });

    @if(old('person_phone',$phone_nos))

        var old_tag1 = {!! json_encode(old('person_phone',$phone_nos)) !!};
        //console.log(old_tag);
        $.each(old_tag1,function(k,v){
            $("#multi_select").append($('<option>', {value: v, text: v}));
        });
        tag1.val(old_tag1).trigger('change'); 
    @endif

    @if(old('person_email',$email))
        var old_tag = {!! json_encode(old('person_email',$email)) !!};
        $.each(old_tag,function(k,v){
            $("#multi_select_email").append($('<option>', {value: v, text: v}));
        });
        tag.val(old_tag).trigger('change'); 
    @endif
    var tag2 = $('#company_id').select2({
        placeholder : "Select Company"
    
    });

    @if(old('company_id',$company_name))
        var old_tag2 = {!! json_encode(old('company_id',$company_name)) !!};
        $('#company_id').val(old_tag2).trigger('change'); 
    @endif
</script>
<?=Html::script('backend/js/form.demo.min.js', [], IS_SECURE)?> 
@include('admin.layout.alert')
@stop