@extends('admin.layout.layout')
@section('style')
    <?=Html::style('backend/css/jquery.fileuploader.css')?>
    <?=Html::style('backend/css/jquery.fileuploader-theme-thumbnails.css')?>
    <?=Html::style('backend/css/bootstrap-fileupload.css')?>
    <?= Html::style('backend/css/multi-select.css') ?>
    <?=Html::style('backend/css/datepicker.css',[], IS_SECURE)?>
    
@stop
@section('start_form')
    <?=Form::model($userupdate_data,['method'=>'POST','class' => 'm-0 form-horizontal','files'=>true,'id'=>'user_edit_form'])?>
@stop
@section('top_fixed_content')
<nav class="navbar navbar-static-top">
    <div class="title">
         <h4></h4>
    </div>
    <div class="pl-10">
        <button type="submit" name="save_button" value="save" id="save_exit_1" class="btn btn-primary btn-sm save save_exit" title="Save" ></i>Save</button>
        <button type="submit" name="save_button" value="save_exit" class="btn btn-primary btn-sm disabled-btn save" title="Save and exit">Save & exit </button>
        <a href="<?= route('systemuser.index') ?>" class="btn btn-default btn-sm" title="Back to users Page">Cancel</a>
    </div>
</nav>
@stop
@section('content')
<div class="row">
    <div class="col-md-12 mb-30">
        <div class="card">
            <div class="card">
            <div class="card-title-w-btn">
                    <h4 class="title">Add User</h4>
            </div><hr>
            <div class="row">
                <div class="col-md-12">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group row">
                                <div class="col-md-12">Address<sup class="text-danger">*</sup></div>
                                <div class="col-md-12">
                                    <?= Form::textarea('address', old('address'),array('class' => 'form-control select_2','placeholder'=>'Address','cols'=>50,'rows'=>5,'id'=>'address_div')) ?>
                                    <span id="address_error" class="help-inline text-danger"><?=$errors->first('address')?></span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-8">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group row">
                                        <div class="col-md-12">Team<sup class="text-danger">*</sup></div>
                                        <div class="col-md-12">
                                            <?= Form::select('team_id',$team_list,old('team_id'),array('class' => 'form-control team team_id_div_error','placeholder'=>'Select Team','id'=>'team_id_div')) ?>
                                            <span id="team_id_error" class="help-inline text-danger"><?=$errors->first('team_id')?></span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group row">
                                        <div class="col-md-12">Designation<sup class="text-danger"></sup></div>
                                        <div class="col-md-12">
                                            <?= Form::select('designation_id',$designation_list,old('designation_id'),array('class' => 'form-control designation','placeholder'=>'Select Designation','id'=>'designation')) ?>
                                            <span  class="help-inline text-danger" style="display: inline-block;"></span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group ">
                                        <div class="col-md-12">Region<sup class="text-danger">*</sup></div>
                                        <div class="col-md-12">
                                            <?= Form::select('region',Config::get('region_list'),old('region'),array('class' => 'form-control region ','placeholder'=> 'Select Region','id'=>'region_div')) ?>
                                            <span id="region_error" class="help-inline text-danger"><?=$errors->first('region')?></span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group row">
                                        <div class="col-md-12">Status<sup class="text-danger">*</sup></div>
                                        <div class="col-md-12">
                                            <?= Form::select('status',[''=>'Select','approve'=>'Approve','reject'=>'Reject','pending' => 'Pending'],old('status'),array('class' => 'form-control status status_div','placeholder'=>'Select Status','id'=>'status_div')) ?>
                                            <span id="status_error" class="help-inline text-danger"><?=$errors->first('status')?></span>
                                        </div>
                                    </div>
                                </div>
                                
                            </div>                        
                        </div>
                    </div>
                </div>
                <div class="col-md-12">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group row">
                                <div class="col-md-12">Company Property<sup class="text-danger">*</sup></div>
                                <div class="col-md-12">
                                    <?= Form::textarea('company_property', old('company_property'),array('class' => 'form-control select_2','placeholder'=>'Company Property','cols'=>50,'rows'=>5,'id'=>'company_property_div')) ?>
                                    <span id="company_property_error" class="help-inline text-danger"><?=$errors->first('company_property')?></span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-8">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group row ">
                                        <div class="col-md-12">Name<sup class="text-danger">*</sup></div>
                                        <div class="col-md-12">
                                            <?=Form::text('name', old('name'), ['class' => 'form-control ', 'placeholder' => 'Name','id'=>'name_div']);?>
                                            <span id="name_error" class="help-inline text-danger"> <?=$errors->first('name') ?> </span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group row ">
                                        <div class="col-md-12">Username<sup class="text-danger">*</sup></div>
                                        <div class="col-md-12">
                                            <?= Form::text('email', old('email'),array('class' => 'form-control select_2 ','placeholder'=>'Username','id'=>'email_div')) ?>
                                            <span id="email_error" class="help-inline text-danger"><?=$errors->first('email')?></span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group row ">
                                        <div class="col-md-12">Company Contact No<sup class="text-danger">*</sup></div>
                                        <div class="col-md-12">
                                            <?=Form::text('company_contact_no', old('company_contact_no'), ['class' => 'form-control number_only', 'placeholder' => 'Company Contact No','id'=>'company_contact_no_div']);?>
                                            <span id="company_contact_no_error" class="help-inline text-danger"><?=$errors->first('company_contact_no')?></span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group row bloodgroup_div_error">
                                        <div class="col-md-12">Date Of Joining<sup class="text-danger">*</sup></div>
                                        <div class="col-md-12">
                                            <?=Form::text('date_of_joining', old('date_of_joining'), ['class' => 'form-control ', 'placeholder' => 'Date Of Joining','id'=>'date_of_joining_div']);?>
                                            <span id="date_of_joining_error" class="help-inline text-danger"><?=$errors->first('date_of_joining')?></span>
                                        </div>
                                    </div>
                                </div> 
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-12">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group row">
                                <div class="col-md-12">Image<sup class="text-danger">*</sup></div>
                                <div class="col-md-12">
                                    <div class="fileupload fileupload-new" data-provides="fileupload">
                                        <div class="fileupload-new thumbnail" style="max-width: 190px; max-height: 150px; line-height: 20px;">
                                            @if(empty($userupdate_data['image']))
                                                <img src="<?=asset('backend/images/no_image.png', IS_SECURE)?>" alt="" />
                                            @else
                                                <img src="<?=asset(LOCAL_IMAGE_PATH.'system_user/'.$userupdate_data['image'], IS_SECURE)?>" alt="" />
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
                                    <div class="form-group row ">
                                        <div class="col-md-12">Date Of Birth<sup class="text-danger">*</sup></div>
                                        <div class="col-md-12">
                                            <?= Form::text('date_of_birth', old('date_of_birth'),array('class' => 'form-control select_2 ','placeholder'=>'Date Of Birth','id'=>'date_of_birth_div')) ?>
                                            <span id="date_of_birth_error" class="help-inline text-danger"><?=$errors->first('date_of_birth')?></span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group row bloodgroup_div_error">
                                        <div class="col-md-12">Blood Group<sup class="text-danger">*</sup></div>
                                        <div class="col-md-12">
                                            <?=Form::text('bloodgroup', old('bloodgroup'), ['class' => 'form-control ', 'placeholder' => 'Blood Group','id'=>'bloodgroup_div']);?>
                                            <span id="bloodgroup_error" class="help-inline text-danger"><?=$errors->first('bloodgroup')?></span>
                                        </div>
                                    </div>
                                </div> 
                                <div class="col-md-6">
                                    <div class="form-group row ">
                                        <div class="col-md-12">Alternate Contact No.<sup class="text-danger">*</sup></div>
                                        <div class="col-md-12">
                                            <?= Form::text('alternate_no', old('alternate_no'),array('class' => 'form-control select_2 number_only','placeholder'=>'Alternate Contact No','id'=>'alternate_no_div')) ?>
                                            <span id="alternate_no_error" class="help-inline text-danger"><?=$errors->first('alternate_no')?></span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group row ">
                                        <div class="col-md-12">Pan No<sup class="text-danger">*</sup></div>
                                        <div class="col-md-12">
                                            <?=Form::text('pan_no', old('pan_no'), ['class' => 'form-control ', 'placeholder' => 'Pan No','id'=>'pan_no_div']);?>
                                            <span id="pan_no_error" class="help-inline text-danger"><?=$errors->first('pan_no')?></span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group row ">
                                        <div class="col-md-12">Aadhar Card<sup class="text-danger">*</sup></div>
                                        <div class="col-md-12">
                                            <?=Form::text('aadhar_no', old('aadhar_no'), ['class' => 'form-control number_only', 'placeholder' => 'Aadhar Card','id'=>'aadhar_no_div']);?>
                                            <span id="aadhar_no_error" class="help-inline text-danger"><?=$errors->first('aadhar_no')?></span>
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
            @include('admin.layout.overlay')
        </div>
        <div id="permission_add" class="row">
            <div class="row">
                @foreach($user_permission as $keys => $single_user_perms)
                    <div class="col-md-4 p-10">
                        <div class="card">
                            <h5 class="subtitle" style="min-height: 30px;font-weight: bold;">
                                <?=  ucwords(str_replace('_',' > ', $keys)) ?>
                            </h5>
                            <hr>
                            <div class="animated-checkbox">
                                <label>
                                    <input type="checkbox" class ="sub_selection" value="<?= $keys ?>"><span class="label-text">Select All</span>
                                </label>
                            </div>
                            <?= Form::select('permission[]',$single_user_perms,old('permission',$user_current_permissions), array('multiple'=>true,'class' => 'multi-select user_permission_multi '.$keys)) ?>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</div>
<div class="text-right">
    <button type="submit" name="save_button" value="save" id="save_exit_2" class="btn btn-primary btn-sm save save_exit" title="Save" ></i>Save</button>
        
    <button type="submit" name="save_button" value="save_exit" class="btn btn-primary btn-sm disabled-btn save save_exit" title="Save & exit">Save & exit</button>
    <a href="<?= route('systemuser.index') ?>" class="btn btn-default btn-sm" title="Back to user Page">Cancel</a>
</div>
@stop

@section('end_form')
<?=Form::close()?>
@stop

@section('script')
    <?= Html::script('backend/js/jquery.form.min.js',[],IS_SECURE) ?>
    <?= Html::script('backend/js/select2.min.js',[],IS_SECURE) ?>    
    <?= Html::script('backend/js/bootstrap-fileupload.js',[],IS_SECURE) ?>
    <?= Html::script('backend/js/jquery.multi-select.js',[],IS_SECURE) ?>
    <?=Html::script('backend/js/bootstrap-datepicker.js', [], IS_SECURE)?>
<script type="text/javascript">
    $(".number_only").keypress(function(h){if(8!=h.which&&0!=h.which&& 32!=h.which&&(h.which<48||h.which>57))return!1});

    function loadDesignation(val)
    {
        var token = "{{csrf_token()}}";

        $.ajax({
            url        : "<?= route('systemuser.getdesignation')?>",
            type       : 'post',
            data       : { "team": val , '_token' : token },
            success    : function(get_designationIn) {
                $('.designation').val('').trigger('change');
                $('.designation').html('');
                var designation_options = $(get_designationIn).html();
                // $(".designation option:selected").val();
                // console.log(designation_options);
                $("#designation").append(designation_options);
            }
        });
    }
    function getPermissionList(val)
    {
        var token = "{{csrf_token()}}";

        $.ajax({
            url        : "<?= route('systemuser.getpermissionlist')?>",
            type       : 'post',
            data       : { "designation_id": val , '_token' : token },
            success    : function(respObj) {
                if(respObj.success == true)
                {
                    $('#permission_add').html(respObj.html);
                }
            }
        });
    }
    $(document).ready(function(){
        $('.team').select2({
            placeholder : "Select Team",
        });
        $('.designation').select2({
            placeholder : "Select Designation",
        });
        $('.status').select2({
            placeholder : "Select Status",
        });
        $('.region').select2({
            placeholder : "Select Region",
        });
        var state_id = $('#team');
        $("#team_id_div").on('change',function() {
            loadDesignation($(this).val());
        });
        var designation_id = $('#designation');
        $("#designation").on('change',function() {
            console.log(designation_id);
            getPermissionList($(this).val());
        });
        var currentDate = new Date();  
        $('#date_of_birth_div').datepicker({
            format: "dd-mm-yyyy",
            autoclose: true,
            todayHighlight: true,
            endDate:currentDate,
        });
        $('#date_of_joining_div').datepicker({
            format: "dd-mm-yyyy",
            autoclose: true,
            todayHighlight: true,
            endDate:currentDate,
        });
        $('.user_permission_multi').multiSelect();

        $("#selectall").click(function(){
            var is_checked = $(this).is(':checked');
            $(".sub_selection").prop('checked',is_checked);
            if (is_checked == true) {
                $('.user_permission_multi').multiSelect('select_all');
            }else{
                $('.user_permission_multi').multiSelect('deselect_all');
            }
        });

        $('.sub_selection').click(function(){
            var is_checked = $(this).is(':checked');
            var sub_selection_value = $(this).val();

            if (is_checked == true) {
                $('.'+sub_selection_value).multiSelect('select_all');
            }else{
                $('.'+sub_selection_value).multiSelect('deselect_all');
            }
        })

        $("input[type='checkbox'][class*='select_']").click(function(){
            var getClass = $(this).attr('class');
            var getSection = getClass.split(' ');
            var getSection = getSection[1].slice(7);
            var is_checked = $(this).is(':checked');
            $("."+getSection).prop('checked',is_checked);
        });

        $(".save").click(function(e){
            console.log('hi');
            e.preventDefault();
            var val = $(this).val();
            var method_type = 'POST';
            var id = '<?= $id?>';
            var update_url = "<?= URL::route('systemuser.update',['id'=>':id']) ?>";
            update_url = update_url.replace(':id',id);
            console.log(update_url);

        
            $('#user_edit_form').ajaxSubmit({
                url: update_url,
                type: method_type,
                data: { "_token" : "<?=csrf_token()?>" ,"id":'<?= $id?>' },
                dataType: 'json',
                
                beforeSubmit : function()
                {
                    console.log('hi2');
                    if(val == 'save')
                    {
                        $('#save_new_1').attr('disabled',true);
                        $('#save_new_1').html('<i class="fa fa-spinner fa-spin"></i> Please wait...');
                        $('#save_new_2').attr('disabled',true);
                        $('#save_new_2').html('<i class="fa fa-spinner fa-spin"></i> Please wait...');
                    }
                    else
                    {
                        $('#save_exit_1').attr('disabled',true);
                        $('#save_exit_1').html('<i class="fa fa-spinner fa-spin"></i> Please wait...');
                        $('#save_exit_2').attr('disabled',true);
                        $('#save_exit_2').html('<i class="fa fa-spinner fa-spin"></i> Please wait...');
                    }
                    $("[id$='_error']").empty();
                    $("[id$='_div']").removeClass('has-error');
                },
                
                success : function(resp)
                {             
                    
                    if (resp.success == true) {
                        var  action = resp.action;
                        toastr.success('User successfully '+action);

                        if(val == 'save')
                        {
                            $('#save_new_1').attr('disabled',false);
                            $('#save_new_1').html('Save & New');
                            $('#save_new_2').attr('disabled',false);
                            $('#save_new_2').html('Save & New');
                            window.location.href = "<?=route('systemuser.edit',$id)?>";
                        }
                        else
                        {
                            $('#save_exit_1').attr('disabled',false);
                            $('#save_exit_1').html('Save');
                            $('#save_exit_2').attr('disabled',false);
                            $('#save_exit_2').html('Save');
                            window.location.href = "<?=route('systemuser.index')?>";
                        }
                    }
                   
                },
                error : function(respObj){    

                    $.each(respObj.responseJSON.errors, function(k,v){
                        $('#'+k+'_error').text(v);
                        $('#'+k+'_div').addClass('has-error');
                    });
                    toastr.error('there were some errors!');
                    if(val == 'save_new')
                    {
                        $('#save_new_1').attr('disabled',false);
                        $('#save_new_1').html('Save & New');
                        $('#save_new_2').attr('disabled',false);
                        $('#save_new_2').html('Save & New');
                    }
                    else
                    {
                        $('#save_exit_1').attr('disabled',false);
                        $('#save_exit_1').html('Save');
                        $('#save_exit_2').attr('disabled',false);
                        $('#save_exit_2').html('Save');
                    }
                    $(".overlay").hide();
                }
            });
        });
    });

    
</script>
@include('admin.layout.alert')
@stop