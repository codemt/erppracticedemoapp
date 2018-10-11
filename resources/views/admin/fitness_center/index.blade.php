@extends('admin.layout.layout')
@section('top_fixed_content')
<nav class="navbar navbar-static-top">
      <!-- Sidebar toggle button-->
    <div class="title">
        <h4></h4>
    </div>
    <div class="pl-10">
        <a href="" class="btn btn-primary btn-sm" title="Add New"> Add New</a>
    </div>
</nav>
@stop
@section('content')
<div class="row">
    <div class="col-md-12">
        <div class="card p-0">
            <div class="card-body table-responsive">
                
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
                                        <li><a href="javascript:void(0);" id="delete" class="delete-btn" title="Delete Single Or Multiple City">Delete</a></li>
                                    </ul>
                                </div>
                            </li>
                        </ul>
                    </div>
                    <div class="clearfix"></div>
                </div>
                <div class="p-20">
                    <table class="table" id="fitness_table">
                        <thead>
                            <tr>
                                <th class="select-all no-sort"><div class="animated-checkbox"><label class="m-0"><input type="checkbox" id="checkAll" /><span class="label-text"></span></label></div></th>
                                
                                <th>Owner Name</th>
                               
                                
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
            @include('admin.layout.overlay')
        </div>
    </div>
</div>
@stop
@section('script')
    <?=Html::script('backend/plugins/datatable/jquery.dataTables.min.js', [], IS_SECURE)?>
    <?=Html::script('backend/plugins/datatable/dataTables.bootstrap.min.js', [], IS_SECURE)?>
    <?=Html::script('backend/js/jquery.form.min.js', [], IS_SECURE)?>
    <?=Html::script('backend/js/fnstandrow.js', [], IS_SECURE)?>
    <?=Html::script('backend/js/delete_script.js', [], IS_SECURE)?>

    <script type="text/javascript">
        var table = "fitness_table";
        var title = "Are you sure to Delete this Record?";
        var text = "You will not be able to recover, once deleted";
        var type = "warning";
        var delete_path = "";
        var token = "<?=csrf_token()?>";
        var page_type = "<?= route('admin.data.index') ?>";

      
        $('[data-toggle="tooltip"]').tooltip();

        $(function(){
            $('#delete').click(function(){
                var delete_id = $('#'+table+' tbody input[type=checkbox]:checked');
                checkLength(delete_id);
            });
            vendor_table = $('#'+table).dataTable({
                "bProcessing": false,
                "bServerSide": true,
                "autoWidth": false,
                "sAjaxSource": page_type,
                "aaSorting": [[ 1, "desc" ]],
                "aoColumns":[
                    {
                        mData: "id",
                        bSortable:false,
                        sWidth:"2%",
                        sClass:"text-center",
                        mRender: function (v, t, o) {
                            return '<div class="animated-checkbox"><label class="m-0"><input class="checkbox" type="checkbox" id="chk_'+v+'" name="special_id[]" value="'+v+'"/><span class="label-text"></span></label></div>';
                        },
                    },
                    {
                        mData: "firstname",
                        bVisible:true,
                        sWidth:"2%",
                        bSortable:true,
                        sClass:"text-center"
                    },
                    

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