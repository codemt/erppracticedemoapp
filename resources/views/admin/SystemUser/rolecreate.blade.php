@extends('admin.layout.layout')
@section('start_form')
    <?= Form::open(['url'=>route('role.store'),'id'=>'login_form','class'=>'login-form', 'method' => 'POST']) ?>           
@stop
@section('top_fixed_content')
<nav class="navbar navbar-static-top">
    <div class="title">
        <!-- <h4><i class="fa fa-city"></i>City</h4> -->
    </div>
    <div class="text-right">
            <button type="submit" name="save_button" value="save_new" class="btn btn-primary btn-sm disabled-btn" title="Save & Add New user">Save & new </button>
            <button type="submit" name="save_button" value="save_exit" class="btn btn-primary btn-sm disabled-btn" title="Save and exit">Save & exit</button>
            <a href="<?=URL::route('role.index')?>" class="btn btn-default btn-sm" title="Back to user Page">Cancel</a>                    
        </div>
</nav>
@stop
@section('content')
<div class="row">
    <div class="col-md-12 mb-30">
        <div class="card">
            <div class="card-title-w-btn">
                <h4 class="title">Create Role Master</h4>
            </div><hr>
            <div class="row"> 
                <div class="col-md-12"> 
                    <div class="col-md-6">                         
                        <div class="form-group @if($errors->has('name')) {{ 'has-error' }} @endif"> 
                            <div class="control-label col-md-4">Role Name<sup class="text-danger">*</sup></div> 
                            <div class="col-md-10">                          
                                <?=Form::text('name',old('name'), ['class' => 'form-control','id' => 'name','placeholder'=>'Role Name']);?> 
                                <span id="name_error" class="help-inline text-danger"><?=$errors->first('name')?></span> 
                            </div> 
                        </div> 
                    </div> 
                    <div class="col-md-6">
                        <div class="form-group @if($errors->has('description')) {{ 'has-error' }} @endif">
                            <div class="control-label col-md-4">Description<sup class="text-danger">*</sup></div>
                            <div class="col-md-10">
                            <?=Form::textarea('description', null, ['class' => 'form-control', 'placeholder' => 'Decription','rows' => 3] );?>
                            <span id="description_error" class="help-inline text-danger"><?=$errors->first('description')?></span>
                        </div>
                        </div>
                    </div>   
                </div>
                <div class="col-md-12"> 
                    <div class="col-md-6">                         
                        <div class="form-group @if($errors->has('status')) {{ 'has-error' }} @endif">
                            <div class="control-label col-md-2">Status</div>
                            <div class="col-md-8">
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
        <div class="text-right">
            <button type="submit" name="save_button" value="save_new" class="btn btn-primary btn-sm disabled-btn" title="Save & Add New user">Save & new </button>
            <button type="submit" name="save_button" value="save_exit" class="btn btn-primary btn-sm disabled-btn" title="Save and exit">Save & exit</button>
            <a href="<?=URL::route('role.index')?>" class="btn btn-default btn-sm" title="Back to user Page">Cancel</a>                    
        </div>
        @include('admin.layout.overlay')
    </div>
</div>
@stop
@section('end_form')
<?=Form::close()?>
@stop

@section('script')

@include('admin.layout.alert')
@stop