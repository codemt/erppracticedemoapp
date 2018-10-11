@extends('admin.layout.layout')
@section('top_fixed_content')
<nav class="navbar navbar-static-top">
    <div class="title">
       <h4></h4>
    </div>
    <div class="col-md-4">
        <form id="frm_filter" name ="frm_filter">
            <div class="form-group">
                <div class="col-md-12">
                    <div class="form-group">
                        <div class="input-group">
                            <div class="input-group-addon"><i class="fa fa-fw fa-calendar"></i></div>
                            <input type="text" name="yoy[]" class="form-control pull-right" id="date_range_1">
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</nav>
@stop
@section('style')
    <?=Html::style('backend/css/dataranger.css', [], IS_SECURE)?>
@stop
@section('content')
<div class="row">
    <div class="col-md-12">
        <div class="card p-0">
            <div class="card-body table-responsive">
                <ul class="nav nav-tabs">
                    
                </ul>
                <div class="text-right">
                    <div class="number-delete">
                        <ul>
                            <li>
                                <p class="mb-0"><span class="num_item"></span>Item Selected.</p>
                            </li>
                            <li class="bulk-dropdown"><a href="javascript:;">Bulk actions<span class="caret"></span></a>
                                <div class="bulk-box">
                                    <div class="bulk-tooltip"></div>
                                    <ul class="bulk-list">
                                        <li><a href="javascript:void(0);" id="delete" class="delete-btn" title="Delete Single Or Multiple Customer">Delete</a></li>
                                    </ul>
                                </div>
                            </li>
                        </ul>
                    </div>
                    <div class="clearfix"></div>
                </div>
                <div class="p-20">
                <table class="table" id="lifafa_table">
                    <thead>
                        <tr>
                            <!-- <th class="select-all no-sort"><div class="animated-checkbox"><label class="m-0"><input type="checkbox" id="checkAll" /><span class="label-text"></span></label></div></th> -->
                            <th>id</th>
                            <th>Event Date</th>
                            
                        </tr>
                    </thead>
                </table>
            </div>
            </div>
            @include('admin.layout.overlay')
        </div>
    </div>
</div>
<!--Start Add State Form -->
<div id="add_data" class="modal fade">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"  onclick="closeModel()">&times;</button>
                    <h4 class="m-0 header_title">User Details</h4>
            </div>
            <div class="modal-body">
                <div class="card bg-gray">
                    <div class="row">
                        <div class="row col-md-6">
                            <div class="col-md-4">
                            <b>Name :</b>
                            </div>
                            <div class="col-md-8" id="u_name">

                            </div>
                        </div>
                        <div class="row col-md-6">
                            <div class="col-md-3">
                            <b> Email:</b>
                            </div>
                            <div class="col-md-9" id="u_email">

                            </div>
                        </div>
                    </div><hr>
                    <div class="row">
                        <div class=" row col-md-6">
                            <div class="col-md-4">
                            <b> Mobile:</b>
                            </div>
                            <div class="col-md-8" id="u_contact">

                            </div>
                        </div>
                        <div class="row col-md-6">
                            <div class="col-md-5">
                            <b>Aadharcard:</b>
                            </div>
                            <div class="col-md-6" id="u_aadhar">

                            </div>
                        </div>
                    </div><hr>
                    <div class="row">
                        <div class=" row col-md-6">
                            <div class="col-md-4">
                            <b>Pancard:</b>
                            </div>
                            <div class="col-md-3" id="u_pancard">

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- End User Form -->
@stop
@section('script')
    <?=Html::script('backend/plugins/datatable/jquery.dataTables.min.js', [], IS_SECURE)?>
    <?=Html::script('backend/plugins/datatable/dataTables.bootstrap.min.js', [], IS_SECURE)?>
    <?=Html::script('backend/js/jquery.form.min.js', [], IS_SECURE)?>
    <?=Html::script('backend/js/moment.min.js', [], IS_SECURE)?>
    <?=Html::script('backend/js/dateranger.js', [], IS_SECURE)?>
    <?=Html::script('backend/js/delete_script.js', [], IS_SECURE)?>

    <script type="text/javascript">

        var table = "lifafa_table";
        var title = "Are you sure to Delete this Record?";
        var text = "You will not be able to recover, once deleted";
        var type = "warning";
        var delete_path = "";
        var token = "<?=csrf_token()?>";
        $('[data-toggle="tooltip"]').tooltip();

        $(function(){
            $('#delete').click(function(){
                var delete_id = $('#'+table+' tbody input[type=checkbox]:checked');
                checkLength(delete_id);
            });
          
            lifafa_table = $('#'+table).dataTable({
                "bProcessing": false,
                "bServerSide": true,
                "autoWidth": false,
                "sAjaxSource": "<?=URL::route('admin.data.index');?>",
                "aaSorting": [[ 1, "desc" ]],
                "aoColumns":[
                    {
                        mData: "id",
                        bSortable:false,
                        sWidth:"2%",
                        bVisible:true,
                        sClass:"text-center",
                       /* mRender: function (v, t, o) {
                            return '<div class="animated-checkbox"><label class="m-0"><input class="checkbox" type="checkbox" id="chk_'+v+'" name="special_id[]" value="'+v+'"/><span class="label-text"></span></label></div>';
                        },*/
                    },
                    {
                        mData: "firstname",
                        bSortable:true,
                        sWidth:"2%",
                        bVisible:true,
                        sClass:"text-center",
                       /* mRender: function (v, t, o) {
                            return '<div class="animated-checkbox"><label class="m-0"><input class="checkbox" type="checkbox" id="chk_'+v+'" name="special_id[]" value="'+v+'"/><span class="label-text"></span></label></div>';
                        },*/
                    }

                ],
                fnPreDrawCallback : function() { $("div.overlay").css('display','flex'); },
                fnDrawCallback : function (oSettings) {
                    $("div.overlay").hide();
                }
            });
        });
        
    </script>
    @include('admin.layout.alert')
@stop