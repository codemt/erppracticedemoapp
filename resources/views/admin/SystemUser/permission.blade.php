@extends('admin.layout.layout')
@section('start_form')
  <?=Form::model($userupdate_data,['method' => 'patch','route' => ['permission.update',$userupdate_data['id']], 'class' => 'm-0 form-horizontal','files'=>true]) ?>             
@stop
@section('top_fixed_content')
<nav class="navbar navbar-static-top">
    <div class="title">      
    </div>
    <div class="text-right">
            <div class="pl-10">
                <button type="submit" name="save_button" value="save_exit" class="btn btn-primary btn-sm disabled-btn" title="Save and exit">Submit</button>
                <a href="<?= route('systemuser.index')?>" class="btn btn-default btn-sm" title="Back to users Page">Cancel</a>
            </div>
        </div>
</nav>
@stop
@section('content')
<div class="row">
    <div class="col-md-12 mb-30">
        <div class="card">
            <div class="card-title-w-btn">
                <h4 class="title">Create Permission</h4>
            </div><hr>
            <div class="row">
                <div class="col-md-12"> 
                    <div class="col-md-6">                         
                        <div class="form-group @if($errors->has('name')) {{ 'has-error' }} @endif"> 
                            <div class="control-label col-md-4">Name</div> 
                            <div class="col-md-10">                          
                                <?=Form::text('name',old('name'), ['class' => 'form-control full', 'data-width' => '100%', 'id' => 'name','readOnly'=>true]);?> 
                                <span id="name_error" class="help-inline text-danger"><?=$errors->first('name')?></span> 
                            </div> 
                        </div> 
                    </div> 
                    <div class="col-md-6">
                        <div class="form-group @if($errors->has('designation')) {{ 'has-error' }} @endif">
                            <div class="control-label col-md-4">Designation</div>
                            <div class="col-md-10">
                            <?=Form::text('designation', null, ['class' => 'form-control','readOnly'=>true]);?>
                            <span id="designation_error" class="help-inline text-danger"><?=$errors->first('designation')?></span>
                        </div>
                        </div>
                    </div>   
                </div>
            </div>            
        </div>   
        <div class="text-right">
            <button type="submit" name="save_button" value="save_exit" class="btn btn-primary btn-sm disabled-btn" title="Save and exit">Submit</button>
            <a href="<?= route('systemuser.index')?>" class="btn btn-default btn-sm" title="Back to user Page">Cancel</a>                    
        </div>@include('admin.layout.overlay')
    </div>
</div>
@stop
@section('end_form')   
    <?=Form::close()?>         
@stop
@section('script')
    <?= Html::script('backend/js/select2.min.js',[],IS_SECURE) ?>
    <script type="text/javascript">
        $(document).ready(function(){
            $('#multi_select').prop('disabled',true);
                $('.select2').select2({
                placeholder : "select country",
            });
        });
    </script>
@include('admin.layout.alert')
@stop