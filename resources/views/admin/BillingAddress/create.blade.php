@extends('admin.layout.layout')
@section('style')
@stop
@section('start_form')
    <?=Form::open(['method' => 'post','route' => 'billing.store', 'class' => 'm-0 form-horizontal'])?>
@stop
@section('top_fixed_content')
<nav class="navbar navbar-static-top">
    <div class="title">
         <h4></h4>
    </div>
    <div class="pl-10">
        <button type="submit" name="save_button" value="save_new" class="btn btn-primary btn-sm disabled-btn" title="Save and add new">Save & New </button>
        <button type="submit" name="save_button" value="save_exit" class="btn btn-primary btn-sm disabled-btn" title="Save and exit">Save & exit </button>
        <a href="<?= route('billing.index') ?>" class="btn btn-default btn-sm" title="Back to users Page">Cancel</a>
    </div>
</nav>
@stop
@section('content')
<div class="row">
    <div class="col-md-12 mb-30">
        <div class="card">
            <div class="card-title-w-btn">
                    <h4 class="title">Create Billing Address</h4>
            </div><hr>
            <div class="row">
                <div class="col-md-12">
                    <div class="col-md-4">
                        <div class="form-group @if($errors->has('title')) has-error @endif">
                            <div class="col-md-12">Title<sup class="text-danger">*</sup></div>
                            <div class="col-md-12">
                                <?= Form::text('title',old('title'),array('class' => 'form-control select_2','placeholder'=>'Title')) ?>
                                <span id="select_2_error" class="help-inline text-danger"><?=$errors->first('title')?></span>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group @if($errors->has('address')) has-error @endif">
                            <div class="col-md-12">Address<sup class="text-danger">*</sup></div>
                            <div class="col-md-12">
                                <?= Form::textarea('address', old('address'),array('class' => 'form-control select_2','cols' => 50, 'rows' => 2)) ?>
                                <span id="select_2_error" class="help-inline text-danger"><?=$errors->first('address')?></span>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group @if($errors->has('area')) has-error @endif">
                            <div class="col-md-12">Area<sup class="text-danger">*</sup></div>
                            <div class="col-md-12">
                                <?= Form::text('area', old('area'),array('class' => 'form-control select_2','placeholder'=>'Area','maxlength' => 50)) ?>
                                <span id="select_2_error" class="help-inline text-danger"><?=$errors->first('area')?></span>
                            </div>
                        </div>
                    </div>                    
                </div>
                <div class="col-md-12">
                    <div class="col-md-4">
                        <div class="form-group @if($errors->has('company_id')) has-error @endif">
                            <div class="col-md-12">Company Name<sup class="text-danger">*</sup></div>
                            <div class="col-md-12">
                                <?= Form::select('company_id',$companyName_list,old('company_id'),array('class' => 'form-control select_2','placeholder'=>'Company Name')) ?>
                                <span id="select_2_error" class="help-inline text-danger"><?=$errors->first('company_id')?></span>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group @if($errors->has('state_id')) has-error @endif">
                            <div class="col-md-12">State<sup class="text-danger">*</sup></div>
                            <div class="col-md-12">
                                <?= Form::select('state_id',$states, old('state_id'),array('class' => 'form-control select_2 number_only','id'=>'state_id')) ?>
                                <span id="select_2_error" class="help-inline text-danger"><?=$errors->first('state_id')?></span>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group @if($errors->has('city_id')) {{ 'has-error' }} @endif">
                            <div class="col-md-12">City<sup class="text-danger">*</sup></div>
                            <div class="col-md-12">
                                <?=Form::select('city_id',$cities, old('city_id'), ['class' => 'form-control','id'=>'city_id']);?>
                                <span id="text_box_error" class="help-inline text-danger"><?=$errors->first('city_id')?></span>
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
        
    <button type="submit" name="save_button" value="save_exit" class="btn btn-primary btn-sm disabled-btn" title="Save & exit">Save & exit</button>
    <a href="<?= route('billing.index') ?>" class="btn btn-default btn-sm" title="Back to user Page">Cancel</a>
</div>
@stop

@section('end_form')
<?=Form::close()?>
@stop

@section('script')
    <script type="text/javascript">
        function getCities(val){
            var token = "{{csrf_token()}}";
            $.ajax({
            url        : '{{ URL::route('search.getcity')}}',
            type       : 'post',
            data       : { "state_id": val , '_token' : token },
            dataType   : 'html',
            success    : function(cities) {
                    var city_options = $(cities).html();
                    $("#city_id").html(city_options);
                }
            });
        }
        $(document).ready(function(){
            $("#state_id").on('change',function() {
                console.log('hi');
                getCities($(this).val());
            });
        });
    </script>
@stop