@extends('admin.layout.layout')
@section('start_form')
    <?=Form::model($user, ['method' => 'PATCH', 'route' => ['users.update', $user->id], 'class' => 'm-0 form-horizontal'])?>
@stop
@section('top_fixed_content')
<nav class="navbar navbar-static-top">
    <div class="title">
         <h4><?=Breadcrumbs::render('edit_user',$user) ?></h4>
    </div>
    <div class="pl-10">
        <button type="submit" name="save_button" value="save_new" class="btn btn-primary btn-sm disabled-btn" title="Save & Add New use">Save </button>
        <a href="javascript;;" onclick="window.history.go(-1); return false;" class="btn btn-default btn-sm" title="Back">Cancel</a>
    </div>
</nav>
@stop
@section('content')
@include('admin.users._form')
<div class="text-right">
   <button type="submit" name="save_button" value="save_new" class="btn btn-primary btn-sm disabled-btn" title="Save & Add New user">Save </button>
    <a href="javascript;;" onclick="window.history.go(-1); return false;" class="btn btn-default btn-sm" title="Back">Cancel</a>
</div>
@stop

@section('end_form')
<?=Form::close()?>
@stop
