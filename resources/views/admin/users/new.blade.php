@extends('admin.layout.layout')
@section('start_form')
    <?=Form::open(['method' => 'POST', 'route' => ['users.store'], 'class' => 'm-0 form-horizontal'])?>
@stop
@section('top_fixed_content')
<nav class="navbar navbar-static-top">
    <div class="title">
         <h4><?=Breadcrumbs::render('create_user') ?></h4>
    </div>
    <div class="pl-10">
        
        <button type="submit" name="save_button" value="save_new" class="btn btn-primary btn-sm disabled-btn" title="Save and add new">Save & New </button>
        <button type="submit" name="save_button" value="save_exit" class="btn btn-primary btn-sm disabled-btn" title="Save and exit">Save & exit</button>
        <a href="<?=URL::route('users.index')?>" class="btn btn-default btn-sm" title="Back to users Page">Cancel</a>
    </div>
</nav>
@stop
@section('content')
@include('admin.users._form')
<div class="text-right">
   <button type="submit" name="save_button" value="save_new" class="btn btn-primary btn-sm disabled-btn" title="Save & Add New user">Save & new </button>
    <button type="submit" name="save_button" value="save_exit" class="btn btn-primary btn-sm disabled-btn" title="Save and exit">Save & exit</button>
    <a href="<?=URL::route('users.index')?>" class="btn btn-default btn-sm" title="Back to user Page">Cancel</a>
</div>
@stop

@section('end_form')

<?=Form::close()?>
@stop
