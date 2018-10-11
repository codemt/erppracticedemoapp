@extends('admin.layout.layout')
@section('start_form')
    <?=Form::model($exists_data,['method' => 'PATCH', 'route' => ['fitness-centers.update',$exists_data->id], 'class' => 'm-0 form-horizontal'])?>
@stop
@section('top_fixed_content')
<nav class="navbar navbar-static-top">
    <div class="title">
         <h4>Add</h4>
    </div>
    <div class="pl-10">
        <button type="submit" name="save_button" value="save_exit" class="btn btn-primary btn-sm disabled-btn" title="Save & New">Save</button>
        <a href="javascript;;" onclick="window.history.go(-1); return false;" class="btn btn-default btn-sm" title="Back">Cancel</a>
    </div>
</nav>
@stop
@section('content')
@include('admin.fitness_center.form')
<div class="text-right">
    <button type="submit" name="save_button" value="save_exit" class="btn btn-primary btn-sm disabled-btn" title="Save & New">Save</button>
    <a href="javascript;;" onclick="window.history.go(-1); return false;" class="btn btn-default btn-sm" title="Back">Cancel</a>
</div>
@stop

@section('end_form')
<?=Form::close()?>
@stop
