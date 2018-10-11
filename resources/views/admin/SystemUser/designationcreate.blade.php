@extends('admin.layout.layout')
@section('start_form')
    <?= Form::open(['url'=>route('designation.store'),'id'=>'login_form','class'=>'login-form', 'method' => 'POST']) ?>           
@stop
@section('top_fixed_content')
<nav class="navbar navbar-static-top">
    <div class="title">
        <!-- <h4><i class="fa fa-city"></i>City</h4> -->
    </div>
    <div class="text-right">
            <button type="submit" name="save_button" value="save_new" class="btn btn-primary btn-sm disabled-btn" title="Save & Add New user">Save & new </button>
            <button type="submit" name="save_button" value="save_exit" class="btn btn-primary btn-sm disabled-btn" title="Save and exit">Save & exit</button>
            <a href="<?=URL::route('designation.index')?>" class="btn btn-default btn-sm" title="Back to user Page">Cancel</a>                    
        </div>
</nav>
@stop
@section('content')
<div class="row">
    <div class="col-md-12 mb-30">
        <div class="card">
            <div class="card-title-w-btn">
                <h4 class="title">Create Designation Master</h4>
            </div><hr>
            <div class="row"> 
                <div class="col-md-12">
                    <div class="row">
                        <div class="col-md-6">                         
                            <div class="form-group row @if($errors->has('name')) {{ 'has-error' }} @endif"> 
                                <div class="col-md-12">Designation Name<sup class="text-danger">*</sup></div> 
                                <div class="col-md-12">                          
                                    <?=Form::text('name',old('name'), ['class' => 'form-control','id' => 'name','placeholder'=>'Designation Name']);?> 
                                    <span id="name_error" class="help-inline text-danger"><?=$errors->first('name')?></span> 
                                </div> 
                            </div> 
                        </div> 
                        <div class="col-md-6">
                            <div class="form-group row @if($errors->has('description')) {{ 'has-error' }} @endif">
                                <div class="col-md-12">Description<sup class="text-danger">*</sup></div>
                                <div class="col-md-12">
                                <?=Form::textarea('description', null, ['class' => 'form-control', 'placeholder' => 'Description','rows' => 1] );?>
                                <span id="description_error" class="help-inline text-danger"><?=$errors->first('description')?></span>
                                </div>
                            </div>
                        </div>
                    </div>   
                </div>
                <div class="col-md-12"> 
                    <div class="row">
                        <div class="col-md-6">                         
                            <div class="form-group row @if($errors->has('team_id')) {{ 'has-error' }} @endif"> 
                                <div class="col-md-12">Team<sup class="text-danger">*</sup></div> 
                                <div class="col-md-12">                          
                                    <?=Form::select('team_id',[''=>'Select Team']+$team,old('team_id'), ['class' => 'form-control team_id','id' => 'team_id']);?> 
                                    <span id="team_id_error" class="help-inline text-danger"><?=$errors->first('team_id')?></span> 
                                </div> 
                            </div> 
                        </div>
                        <div class="col-md-6">                         
                            <div class="form-group row @if($errors->has('status')) {{ 'has-error' }} @endif">
                                <div class="col-md-12">Status</div>
                                <div class="col-md-12">
                                    <div class="animated-radio-button pull-left mr-10">
                                        <label for="status_true">
                                            <?=Form::radio('status', 1, true,['id' => 'status_true'])?>
                                            <span class="label-text"></span> Active
                                        </label>
                                    </div>
                                    <div class="animated-radio-button pull-left">
                                        <label for="status_false">
                                            <?=Form::radio('status', 0,null, ['id' => 'status_false'])?>
                                            <span class="label-text"></span> Deactive
                                        </label>
                                    </div>
                                </div>
                            </div> 
                        </div> 
                    </div>
                </div>
            </div>    
        </div>   
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
                        <?= Form::select('permission[]',$single_user_perms,null, array('multiple'=>true,'class' => 'multi-select user_permission_multi '.$keys)) ?>
                    </div>
                </div>
            @endforeach
        </div>
        <div class="text-right">
            <button type="submit" name="save_button" value="save_new" class="btn btn-primary btn-sm disabled-btn" title="Save & Add New user">Save & new </button>
            <button type="submit" name="save_button" value="save_exit" class="btn btn-primary btn-sm disabled-btn" title="Save and exit">Save & exit</button>
            <a href="<?=URL::route('designation.index')?>" class="btn btn-default btn-sm" title="Back to user Page">Cancel</a>                    
        </div>
        @include('admin.layout.overlay')
    </div>
</div>
@stop
@section('end_form')
<?=Form::close()?>
@stop
@section('style')
<?= Html::style('backend/css/multi-select.css',[],IS_SECURE) ?>
@stop
@section('script')
<?= Html::script('backend/js/select2.min.js',[],IS_SECURE) ?> 
<?= Html::script('backend/js/jquery.multi-select.js',[],IS_SECURE) ?>
<?= Html::script('backend/js/jquery.form.min.js',[],IS_SECURE) ?>

    <script type="text/javascript">
        $(document).ready(function(){
            $('.team_id').select2({
                placeholder : "Select Team",
            });
        });
        $('.user_permission_multi').multiSelect();
        $('.user_permission_multi').multiSelect('deselect_all');

        $('form').submit(function(){
            $('.overlay').show();
        });

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
    </script>
@include('admin.layout.alert')
@stop