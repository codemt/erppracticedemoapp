@extends('admin.layout.layout')
@section('start_form')
    <?=Form::open(['method' => 'GET', 'class' => 'm-0 form-horizontal'])?>
@stop
@section('top_fixed_content')
<nav class="navbar navbar-static-top">
    <div class="title">
         <h4><i class="fa fa-user"></i>Form</h4>
    </div>
    <div class="pl-10">
        <button type="submit" name="save_button" value="save_new" class="btn btn-primary btn-sm disabled-btn" title="Save">Save </button>
        <a href="#" class="btn btn-default btn-sm" title="Back to users Page">Cancel</a>
    </div>
</nav>
@stop
@section('content')
@include('admin.dynamic-form._form')
<div class="text-right">
   <button type="submit" name="save_button" value="save_new" class="btn btn-primary btn-sm disabled-btn" title="Save & Add New form">Save </button>
    <a href="#" class="btn btn-default btn-sm" title="Back to form Page">Cancel</a>
</div>
@stop

@section('end_form')
<?=Form::close()?>
@stop
@section('script')

<?=Html::script('backend/js/jquery.form.min.js', [], IS_SECURE)?>
<?= Html::script('backend/plugins/dynamicform/dynamicform.js',[],IS_SECURE) ?>
<script type="text/javascript">
    $(document).ready(function(){

        var dynamic_form1 = $("#student").dynamicForm("#student_add", "#student_remove", {
            limit:4,
            normalizeFullForm: false
        });
        dynamic_form1.inject( <?= json_encode(old('student.student')) ?> );

        @if($errors)
        var detail_Errors = <?= json_encode($errors->toArray()) ?>;
        $.each(detail_Errors, function(id,msg){
            var id_arr = id.split('.');

            if (id_arr[3] == 'city') {
                $('#'+id_arr[3]+id_arr[id_arr.length-2]).closest('div').find('span').text(msg[0]);
            }
            if (id_arr[3] == 'mobile') {
                $('#'+id_arr[3]+id_arr[id_arr.length-2]).closest('div').find('span').text(msg[0]);
            }
            if (id_arr[3] == 'pincode') {
                $('#'+id_arr[3]+id_arr[id_arr.length-2]).closest('div').find('span').text(msg[0]);
            }
        });
        @endif
    });
</script>
@stop
