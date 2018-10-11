@extends('admin.layout.layout')
@section('style')
    <?=Html::style('backend/plugins/sweetalert/sweetalert.min.css', [], IS_SECURE)?>
    <?=Html::style('backend/css/custom1.css', [], IS_SECURE)?>
    <style type="text/css">
        .box__dragndrop,
        .box__uploading,
        .box__success,
        .box__error {
            display: none;
        }
    </style>
@stop
@section('top_fixed_content')
<nav class="navbar navbar-static-top">
    <div class="title">
        <h4><i class="fa fa-user"></i>All Input</h4>
    </div>
    <div class="top_filter"></div>
    <div class="pl-10">
          <a href="/admin/input/form" class="btn btn-primary btn-sm" title="Add USERS" data-toggle="modal">Add USERS</a>
    </div>
</nav>
@stop
@section('content')
<form method="post" action="#" files="true" class="my-form" id="Myform">
    <div class="modal fade" id="imagemodal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
      <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close close_model" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only" class="close_btn">Close</span></button>
                <h4 class="modal-title" id="myModalLabel">Image</h4>
            </div>
            <div class="modal-body">
                <div class="inputfile-box">
                    <input class="filebutton" name="excel_file" id="file" type="file" onchange="uploadFile(this);">
                    <label for="file"><span class="file-box text-center" id="file-name">Upload Excel file that matches the Template or Export file structure<br>(click here)</span><span class="file-button"></span></label>

                    <span class="text-danger"><?= $errors->first('excel_file') ?></span>
                    @if(Session::has('message'))
                        <span class="text-danger"><?= Session::get('message') ?></span>
                    @endif
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" data-dismiss="modal">Save</button>
                <button type="button" class="btn btn-default close_model" class="close_btn" data-dismiss="modal">Close</button>
            </div>
        </div>
      </div>
    </div>
</form>
@stop

<!-- for error list show in model -->
@section('script')
<?=Html::script('backend/js/jquery.form.min.js', [], IS_SECURE)?>
<?=Html::script('backend/plugins/datatable/jquery.dataTables.min.js', [], IS_SECURE)?>
<?=Html::script('backend/plugins/datatable/dataTables.bootstrap.min.js', [], IS_SECURE)?>
<?=Html::script('backend/plugins/sweetalert/sweetalert.min.js', [], IS_SECURE)?>
<?=Html::script('backend/js/fnstandrow.js', [], IS_SECURE)?>
<?=Html::script('backend/js/delete_script.js', [], IS_SECURE)?>
<?= Html::script('backend/js/select2.min.js',[],IS_SECURE) ?>
<script type="text/javascript">
    
    $("#pop").on("click", function() {
        $('#imagepreview').attr('src', $('#imageresource').attr('src')); // here asign the image to the modal when the user click the enlarge link
        $('#imagemodal').modal('show'); 
    });

    function uploadFile(e) {
        document.getElementById("file-name").innerHTML = e.files[0].name
    }

   
    $('.close_model').click(function(){
        window.location.reload();
    });
</script>

@include('admin.layout.alert')
@stop