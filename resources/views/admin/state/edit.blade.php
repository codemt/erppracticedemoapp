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
        <?=Form::model($state, ['method' => 'PATCH', 'route' => ['state.update', $state['id']], 'role' => 'form'])?>
@stop
@section('top_fixed_content')
<nav class="navbar navbar-static-top">
    <div class="title">
    </div>
    <div class="text-right">
        <button type="submit" name="save_button" value="save_new" class="btn btn-primary btn-sm disabled-btn" title="Save & Exit">Save</button>
        <button type="submit" name="save_button" value="save_exit" class="btn btn-primary btn-sm disabled-btn" title="Save and exit">Save & exit </button>
        <a href="<?=URL::route('state.index')?>" class="btn btn-default btn-sm" title="Back to user Page">Cancel</a>                    
    </div>
</nav>
@stop
@section('content')
<div class="row">
    <div class="col-md-12 mb-30">
        <div class="card">
            <div class="card-title-w-btn">
                <h4 class="title">state</h4>
            </div><hr>
            <div class="row">
                <div class="col-md-12">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group @if($errors->has('title')) 'has-error' @endif">
                                <label>State<sup class="text-danger">*</sup></label>
                                <?=Form::text('title', null, ['class' => 'form-control', 'placeholder' => 'Enter State Name']);?> 
                                <span id="title_error" class="help-inline text-danger"><?=$errors->first('title')?></span>
                            </div>
                        </div>   
                    </div>
                </div>
            </div>           
        </div>   
		<div class="text-right">
			<button type="submit" name="save_button" value="save_new" class="btn btn-primary btn-sm disabled-btn" title="Save & Exit">Save</button>
            <button type="submit" name="save_button" value="save_exit" class="btn btn-primary btn-sm disabled-btn" title="Save and exit">Save & exit </button>
			<a href="<?=URL::route('state.index')?>" class="btn btn-default btn-sm" title="Back to user Page">Cancel</a>                    
 		</div>
		<?=Form::close()?>
		@include('admin.layout.overlay')
	</div>
</div>
@stop
@section('script')

@include('admin.layout.alert')
@stop