@extends('admin.layout.layout')
@section('style')
    <?=Html::style('backend/plugins/sweetalert/sweetalert.min.css', [], IS_SECURE)?>
    <?= Html::style('backend/css/dataranger.css',[],IS_SECURE) ?>
    <style type="text/css">
        .box__dragndrop,
        .box__uploading,
        .box__success,
        .box__error {
            display: none;
        }
    </style>
@stop
@section('top_fixed_content')
<nav class="navbar navbar-static-top">
    <div class="title">
        <!-- <h4><i class="fa fa-city"></i>City</h4> -->
    </div>
</nav>
@stop
@section('content')
<div class="row">
    <div class="col-md-12 mb-30">
        <div class="card">
            <div class="card-title-w-btn">
                <h4 class="title">City</h4>
            </div><hr>
       		<?= Form::open(['url'=>route('admin.insertcity'),'id'=>'login_form','class'=>'login-form', 'method' => 'POST']) ?>
            <div class="row">
                <div class="col-md-12">
                    <div class="col-md-6">
                        <div class="form-group @if($errors->has('city')) {{ 'has-error' }} @endif">
                            <div class="control-label col-md-4">City Name<sup class="text-danger">*</sup></div>
                            <div class="col-md-8">
                            <?=Form::text('city', null, ['class' => 'form-control', 'placeholder' => 'Enter City Name']);?>
                            <span id="city_error" class="help-inline text-danger"><?=$errors->first('city')?></span>
                        </div>
                        </div>
                    </div>   
                </div>
            </div>            
        </div>   
		<div class="text-right">
			<button type="submit" name="save_button" value="save_new" class="btn btn-primary btn-sm disabled-btn" title="Save & Add New user">Save & new </button>
			<button type="submit" name="save_button" value="save_exit" class="btn btn-primary btn-sm disabled-btn" title="Save and exit">Save & exit</button>
			<a href="<?=URL::route('city.index')?>" class="btn btn-default btn-sm" title="Back to user Page">Cancel</a>                    
 		</div>
		<?=Form::close()?>
		@include('admin.layout.overlay')
	</div>
</div>

   
@include('admin.layout.alert')
@stop