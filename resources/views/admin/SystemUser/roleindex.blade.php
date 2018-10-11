@extends('admin.layout.layout')
@section('top_fixed_content')
<nav class="navbar navbar-static-top">
    <div class="title">
        <h4><i class="fa fa-list"></i>Role Master</h4>
    </div>
    <div class="top_filter"></div>
    <div class="pl-10">
        <a href="<?= route('role.create') ?>" class="btn btn-primary btn-sm" title="Add New">Add New</a>
    </div>
</nav>
@stop
@section('content')
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-body table-responsive">
                <div class="text-right">
                    <div class="clearfix"></div>
                </div>
                <table id="role_table" class="table table-hover">
                    <thead>
                        <tr>
                            <th>Role Name</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
            </div>
            @include('admin.layout.overlay')
        </div>
    </div>
</div>
@stop
@section('script')
<?=Html::script('backend/plugins/datatable/jquery.dataTables.min.js', [], IS_SECURE)?>
<?=Html::script('backend/plugins/datatable/dataTables.bootstrap.min.js', [], IS_SECURE)?>
<?=Html::script('backend/plugins/sweetalert/sweetalert.min.js', [], IS_SECURE)?>
<?=Html::script('backend/js/fnstandrow.js', [], IS_SECURE)?>

<script type="text/javascript">
    var token = "<?= csrf_token() ?>";  

    $(function(){
       $('#role_table').DataTable({
            "bProcessing": false,
            "bServerSide": true,
            "autoWidth": false,
            "sAjaxSource": '<?= route('role.index')?>',
            "aaSorting": [ 1,"desc"],
            "aoColumns": [
            {   mData:"name",sWidth:"60%",bSortable : true,

                mRender : function(v,t,o){  

                    var edit_path = "<?= route('role.edit',['id'=>':id']) ?>";
                    edit_path = edit_path.replace(':id',o['id']);

                    var act_html  = '<a title="Edit '+o['name']+'" href="'+edit_path+'">'+ v +'</a>'

                    return act_html;
                }
            },
            {   mData:"status",sWidth:"15%",bSortable : true,
                mRender: function(v, t, o) {
                    if(v == 1)
                    {
                        return "<span class='badge bg-info'>Yes</span>";
                    }
                    else
                    {
                        return "<span class='badge bg-default'>No</span>";
                    }
                },
                
            },

        ],
        fnPreDrawCallback : function() { $("div.overlay").css('display','flex'); },
        fnDrawCallback : function (oSettings) {
            $("div.overlay").hide();
        },
    });
    });

</script>
@include('admin.layout.alert')
@stop