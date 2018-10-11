@extends('admin.layout.layout')
@section('start_form')
  <?=Form::model($country, ['method' => 'PATCH', 'route' => ['country.update', $country['id']], 'role' => 'form'])?>                          
@stop
@section('top_fixed_content')
<nav class="navbar navbar-static-top">
    <div class="title">
    </div>
    <div class="text-right">
            <div class="pl-10">
                <button type="submit" name="save_button" value="save_new" class="btn btn-primary btn-sm disabled-btn" title="Save and add new">Save</button>
                <button type="submit" name="save_button" value="save_exit" class="btn btn-primary btn-sm disabled-btn" title="Save and exit">Save & exit </button>
                <a href="<?= route('country.index') ?>" class="btn btn-default btn-sm" title="Back to users Page">Cancel</a>
            </div>
        </div>
</nav>
@stop
@section('content')
<div class="row">
    <div class="col-md-12 mb-30">
        <div class="card">
            <div class="card-title-w-btn">
                <h4 class="title">Edit Country Master</h4>
            </div><hr>
       		<div class="row">
                <div class="col-md-12">
                    <div class="col-md-6">
                        <div class="form-group @if($errors->has('title')) {{ 'has-error' }} @endif">
                            <div class="control-label col-md-4">Country<sup class="text-danger">*</sup></div>
                            <div class="col-md-8">
                            <?=Form::text('title', null, ['class' => 'form-control', 'placeholder' => 'Enter Country']);?>
                            <span id="title_errors" class="help-inline text-danger"><?=$errors->first('title')?></span>
                        </div>
                        </div>
                    </div>   
                </div>
            </div>            
        </div>   
		<div class="text-right">
            <div class="pl-10">
                <button type="submit" name="save_button" value="save_new" class="btn btn-primary btn-sm disabled-btn" title="Save and add new">Save</button>
                <button type="submit" name="save_button" value="save_exit" class="btn btn-primary btn-sm disabled-btn" title="Save and exit">Save & exit </button>
                <a href="<?= route('country.index') ?>" class="btn btn-default btn-sm" title="Back to users Page">Cancel</a>
            </div>
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