@extends('admin.layout.layout')
                <?=Form::open(array('url' => 'admin/login', 'class' => 'form-horizontal','method'=>'get'))?>
@section('top_fixed_content')
<nav class="navbar navbar-static-top">
    <div class="title">
        <h4></h4>
    </div>
    <div class="pl-10">
        <button type="submit" name="save_button" value="save_new" class="btn btn-primary btn-sm disabled-btn" title="Save change password">Save </button>
        <a href="" class="btn btn-default btn-sm" title="close without change password">Cancel</a>
    </div>
</nav>
@stop
@section('content')
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-title-w-btn">
                <h3 class="title">Change Password</h3>
            </div>
            <hr>
            <div class="card-body">
                    <div class="col-md-12">
                        <div class="col-md-2"></div>
                        <div class="col-md-8">
                            <div class="form-group">
                                <label>Old Password<sup class="text-danger">*</sup></label>
                                <?=Form::password('old_password', ['placeholder' => 'Old Password', 'class' => 'form-control'])?>
                                <span class="text-danger"><?=$errors->first('old_password')?></span>
                            </div>
                            <div class="form-group">
                                <label>New Password<sup class="text-danger">*</sup></label>
                                <?=Form::password('password', ['class' => 'form-control', 'placeholder' => "New Password"])?>
                                <span class="text-danger"><?=$errors->first('password')?></span>
                            </div>
                            <div class="form-group">
                                <label>Confirm Password<sup class="text-danger">*</sup></label>
                                <?=Form::password('password_confirmation', ['class' => 'form-control', 'placeholder' => "Confirm New Password"])?>
                                <span class="text-danger"><?=$errors->first('password_confirmation')?></span>
                            </div>
                        </div>
                        <div class="col-md-2"></div>
                    </div>
                    <div class="clearfix"></div>
                    @include('admin.layout.overlay')
                   
                <?=Form::close()?>
            </div>
        </div>
    </div>
</div>
<div class="text-right">                    
    <button type="submit" class="btn btn-primary btn-sm" title="Save change password">Save</button>&nbsp;&nbsp;&nbsp;
    <a href="" class="btn btn-default btn-sm" title="close without change password">Cancel</a>
</div>
@stop
@section('script')
	@include('admin.layout.alert')
@stop

