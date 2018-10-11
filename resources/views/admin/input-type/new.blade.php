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
        <button type="submit" name="save_button" value="save_new" class="btn btn-primary btn-sm disabled-btn" title="Save and add new">Save & New </button>
        <button type="submit" name="save_button" value="save_exit" class="btn btn-primary btn-sm disabled-btn" title="Save and exit">Save & exit </button>
        <a href="/admin/input" class="btn btn-default btn-sm" title="Back to users Page">Cancel</a>
    </div>
</nav>
@stop
@section('content')
<div class="row">
    <div class="col-md-12 mb-30">
        <div class="card">
            <div class="card-title-w-btn">
                    <h4 class="title">All Input</h4>
            </div><hr>
            <div class="row">
                <div class="col-md-12">
                    <div class="col-md-6">
                        <div class="form-group @if($errors->has('text_box')) {{ 'has-error' }} @endif">
                            <div class="control-label col-md-4">Text Box<sup class="text-danger">*</sup></div>
                            <div class="col-md-8">
                                <?=Form::text('text_box', old('text_box'), ['class' => 'form-control', 'placeholder' => 'First Name']);?>
                                <span id="text_box_error" class="help-inline text-danger"><?=$errors->first('text_box')?></span>
                            </div>
                        </div>
                    </div>
            
                    <div class="col-md-6">
                        <div class="form-group @if($errors->has('password')) has-error @endif">
                            <div class="control-label col-md-4">Password<sup class="text-danger">*</sup></div>
                            <div class="col-md-8">
                                <?=Form::password('password',['id'=>'password','class' => 'form-control', 'placeholder' => 'Password']);?>
                                <span id="password_error" class="help-inline text-danger"><?=$errors->first('password')?></span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-12">
                    <div class="col-md-6">
                        <div class="form-group @if($errors->has('email')) {{ 'has-error' }} @endif">
                            <div class="control-label col-md-4">Email<sup class="text-danger">*</sup></div>
                            <div class="col-md-8">
                                <?=Form::email('email', old('email'), ['class' => 'form-control', 'placeholder' => 'Email']);?>
                                <span id="email_error" class="help-inline text-danger"><?=$errors->first('email')?></span>
                            </div>
                        </div>
                    </div>

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
                    <div class="col-md-6">
                        <div class="form-group @if($errors->has('dob')) {{ 'has-error' }} @endif">
                            <div class="control-label col-md-4">Date<sup class="text-danger">*</sup></div>
                            <div class="col-md-8">
                                <?= Form::date('dob', old('dob'),array('class' => 'form-control','id'=>'dob')) ?>
                                <span id="dob_error" class="help-inline text-danger"><?=$errors->first('dob')?></span>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group @if($errors->has('number')) {{ 'has-error' }} @endif">
                            <div class="control-label col-md-4">Number <sup class="text-danger">*</sup></div>
                            <div class="col-md-8">
                                <?= Form::number('number', old('number'),array('class' => 'form-control','id'=>'number','placeholder'=>'number')) ?>
                                <span id="number_error" class="help-inline text-danger"><?=$errors->first('number')?></span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-12">
                    <div class="col-md-6">
                        <div class="form-group @if($errors->has('select_2')) has-error @endif">
                            <div class="control-label col-md-4">Select 2<sup class="text-danger">*</sup></div>
                            <div class="col-md-8">
                                <?= Form::select('select_2[]',['all'=>'All','single'=>'Single','double'=>'Double'], old('select_2'),array('class' => 'form-control select_2','id'=>'select_2','placeholder'=>'type name')) ?>
                                <span id="select_2_error" class="help-inline text-danger"><?=$errors->first('select_2')?></span>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group @if($errors->has('mul_select')) has-error @endif">
                            <div class="control-label col-md-4">multi-Select<sup class="text-danger">*</sup></div>
                            <div class="col-md-8">
                                <?= Form::select('mul_select[]',['all'=>'All','single'=>'Single','double'=>'Double'], old('mul_select'),array('class' => 'form-control select2','id'=>'multi_select','multiple'=>true)) ?>
                                <span id="mul_select_error" class="help-inline text-danger"><?=$errors->first('mul_select')?></span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-12">
                    <div class="col-md-6">
                        <div class="form-group @if($errors->has('address')) {{ 'has-error' }} @endif">
                            <div class="control-label col-md-4">Radio<sup class="text-danger">*</sup></div>
                            <div class="col-md-8">
                                <div class="animated-radio-button pull-left mr-10">
                                    <label class="control-label" for="is_active_true">
                                        <?=Form::radio('is_active', 1, true, ['id' => 'is_active_true'])?>
                                        <span class="label-text"></span> Active
                                    </label>
                                </div>
                                <div class="animated-radio-button pull-left">
                                    <label class="control-label" for="is_active_false">
                                        <?=Form::radio('is_active', 0, false, ['id' => 'is_active_false'])?>
                                        <span class="label-text"></span> Deactive
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group @if($errors->has('address')) {{ 'has-error' }} @endif">
                            <div class="control-label col-md-4">Checkbox<sup class="text-danger">*</sup></div>
                            <div class="col-md-8">
                                <div class="animated-checkbox pull-left mr-10">
                                    <label class="control-label">
                                        <input type="checkbox" value="1" class="form-control" name="chkd">
                                        <span class="label-text">chk1</span>
                                    </label>
                                </div>
                                <div class="animated-checkbox pull-left mr-10">
                                    <label class="control-label">
                                        <input type="checkbox" value="2" class="form-control" name="chkd">
                                        <span class="label-text">chk2</span>
                                    </label>
                                </div>
                                <div class="animated-checkbox pull-left mr-10">
                                    <label class="control-label">
                                        <input type="checkbox" value="3" class="form-control" name="chkd">
                                        <span class="label-text">chk3</span>
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-12">
                    <div class="col-md-6">
                        <div class="form-group @if($errors->has('file')) {{ 'has-error' }} @endif">
                            <div class="control-label col-md-4">File<sup class="text-danger">*</sup></div>
                            <div class="col-md-8">
                                <?=Form::file('file', null, ['class' => 'form-control', 'placeholder' => 'First Name']);?>
                                <span id="firstname_error" class="help-inline text-danger"><?=$errors->first('file')?></span>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group @if($errors->has('file')) {{ 'has-error' }} @endif">
                            <div class="control-label col-md-4">Image<sup class="text-danger">*</sup></div>
                            <div class="col-md-8">
                                <div class="fileupload fileupload-new" data-provides="fileupload">
                                    <div class="fileupload-new thumbnail" style="max-width: 190px; max-height: 150px; line-height: 20px;">
                                        <img src="<?=asset('backend/images/no_image.png', IS_SECURE)?>" alt="" />
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
                                    <span id="image_error" class="help-inline text-danger"><?=$errors->first('image')?></span>
                                </div>
                            </div>
                        </div>
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