@extends('admin.layout.layout')
@section('style')
    <?=Html::style('backend/css/jquery.fileuploader.css')?>
    <?=Html::style('backend/css/jquery.fileuploader-theme-thumbnails.css')?>
    <?=Html::style('backend/css/bootstrap-fileupload.css')?>
@stop
@section('start_form')
    <?=Form::open(['method' => 'GET', 'url'=>'/admin/input/form', 'class' => 'm-0 form-horizontal'])?>
@stop
@section('top_fixed_content')
<nav class="navbar navbar-static-top">
    <div class="title">
         <h4></h4>
    </div>
    <div class="pl-10">
        <button type="submit" name="save_button" value="save_new" class="btn btn-primary btn-sm disabled-btn" title="Send and Send New">Send & New </button>
        <button type="submit" name="save_button" value="save_exit" class="btn btn-primary btn-sm disabled-btn" title="Send and Exit">Send & exit </button>
        <a href="/admin/input" class="btn btn-default btn-sm" title="Back to users Page">Cancel</a>
    </div>
</nav>
@stop
@section('content')
<div class="row">
    <div class="col-md-12 mb-30">
        <div class="card">
            <div class="card-title-w-btn">
                    <h4 class="title">Send Emails </h4>
            </div><hr>
            <div class="row">
                <div class="col-md-12">
                    <div class="col-md-6">
                        <div class="form-group @if($errors->has('text_box')) {{ 'has-error' }} @endif">
                            <div class="control-label col-md-4">From <sup class="text-danger">*</sup></div>
                            <div class="col-md-8">
                                <?=Form::text('text_box', old('text_box'), ['class' => 'form-control', 'placeholder' => 'From']);?>
                                <span id="text_box_error" class="help-inline text-danger"><?=$errors->first('text_box')?></span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-12">
                    <div class="col-md-6">
                        <div class="form-group @if($errors->has('email')) {{ 'has-error' }} @endif">
                            <div class="control-label col-md-4">CC<sup class="text-danger">*</sup></div>
                            <div class="col-md-8">
                                <?=Form::email('email', old('email'), ['class' => 'form-control', 'placeholder' => 'cc']);?>
                                <span id="email_error" class="help-inline text-danger"><?=$errors->first('email')?></span>
                            </div>
                        </div>
					</div>
					<div class="col-md-12">
					<div class="col-md-6">
							<div class="form-group @if($errors->has('email')) {{ 'has-error' }} @endif">
								<div class="control-label col-md-4">BCC<sup class="text-danger">*</sup></div>
								<div class="col-md-8">
									<?=Form::email('email', old('email'), ['class' => 'form-control', 'placeholder' => 'bcc']);?>
									<span id="email_error" class="help-inline text-danger"><?=$errors->first('email')?></span>
								</div>
							</div>
						</div>
					<div class="col-md-12">	
                    <div class="col-md-6">
                        <div class="form-group @if($errors->has('address')) {{ 'has-error' }} @endif">
                            <div class="control-label col-md-4">TextArea<sup class="text-danger">*</sup></div>
                            <div class="col-md-8">
                                <?=Form::textarea('address', null, ['class' => 'form-control', 'placeholder' => 'Address','rows'=>'4']);?>
                                <span id="address_error" class="help-inline text-danger"><?=$errors->first('address')?></span>
                            </div>
                        </div>
                    </div>
                </div>
                             
                <div class="col-md-12">
					<div class="col-md-12">
                </div>
                <div class="col-md-12">
					<div class="col-md-12">
                </div>
                <div class="col-md-12">
					<div class="col-md-12">
                </div>
                </div>

            </div>
            @include('admin.layout.overlay')
        </div>
    </div>
</div>
<div class="text-right">
    <button type="submit" name="save_button" value="save_new" class="btn btn-primary btn-sm disabled-btn" title="Save and add new user">Save & New </button>
        
    <button type="submit" name="save_button" value="save_new" class="btn btn-primary btn-sm disabled-btn" title="Save & exit">Save & exit</button>
    <a href="/admin/input" class="btn btn-default btn-sm" title="Back to user Page">Cancel</a>
</div>
@stop

@section('end_form')
<?=Form::close()?>
@stop
@section('script')
<?= Html::script('backend/js/select2.min.js',[],IS_SECURE) ?>
<?= Html::script('backend/js/bootstrap-fileupload.js',[],IS_SECURE) ?>

    <script type="text/javascript">
        $('.select2').select2({
            placeholder : "status",
        });
        $('.select_2').select2({
            placeholder : "status",
        });
    </script>
@stop