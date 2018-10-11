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
@section('start_form')
        <?=Form::model($exist_data,['method' => 'PATCH', 'route' => ['cities.update', $exist_data['id']], 'role' => 'form'])?> 
@stop
@section('top_fixed_content')
<nav class="navbar navbar-static-top">
    <div class="title">
        <!-- <h4><i class="fa fa-city"></i>City</h4> -->
    </div>
    <div class="text-right">
        <button type="submit" name="save_button" value="save_new" class="btn btn-primary btn-sm disabled-btn" title="Save & Exit">Save</button>
        <button type="submit" name="save_button" value="save_exit" class="btn btn-primary btn-sm disabled-btn" title="Save and exit">Save & exit </button>
        <a href="<?=URL::route('cities.index')?>" class="btn btn-default btn-sm" title="Back to user Page">Cancel</a>                    
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
 
            <div class="row"> 
                <div class="col-md-12"> 
                    <div class="col-md-6">  
                        <div class="form-group @if($errors->has('state')) 'has-error' @endif">
                            <label>State<sup class="text-danger">*</sup></label>
                            <?=Form::select('state',$state, old('state',$exist_data['state_id']), ['class' => 'form-control full', 'data-width' => '100%', 'id' => 'state']);?> 
                            <span id="state_error" class="help-inline text-danger"><?=$errors->first('state')?></span>
                        </div>
                    </div> 
                    <div class="col-md-6">  
                        <div class="form-group @if($errors->has('title')) 'has-error' @endif">
                            <label>City<sup class="text-danger">*</sup></label>
                            <?=Form::text('title', old('title'), ['class' => 'form-control', 'placeholder' => 'Enter title Name']);?>
                            <span id="title_error" class="help-inline text-danger"><?=$errors->first('title')?></span>
                        </div>
                    </div>
                </div> 
            </div>           
        </div>   
		<div class="text-right">
			<button type="submit" name="save_button" value="save_new" class="btn btn-primary btn-sm disabled-btn" title="Save & Exit">Save</button>
            <button type="submit" name="save_button" value="save_exit" class="btn btn-primary btn-sm disabled-btn" title="Save and exit">Save & exit </button>
			<a href="<?=URL::route('cities.index')?>" class="btn btn-default btn-sm" title="Back to user Page">Cancel</a>                    
 		</div>
		<?=Form::close()?>
		@include('admin.layout.overlay')
	</div>
</div>
@stop
@section('script')

@include('admin.layout.alert')
@stop