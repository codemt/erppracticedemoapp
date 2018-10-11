@extends('admin.layout.layout')
@section('top_fixed_content')
<nav class="navbar navbar-static-top">
    <div class="title">
        <h4><i class="fa fa-list"></i>Users</h4>
    </div>
    <div class="top_filter"></div>
    <div class="pl-10">
        @if(App\Helpers\DesignationPermissionCheck::isPermitted('systemuser.create') && App\Helpers\DesignationPermissionCheck::isPermitted('systemuser.store'))
            <a href="<?= route('systemuser.create') ?>" class="btn btn-primary btn-sm" title="Add New">Add User</a>
        @endif
        <a href="<?= route('systemUser.export')?>" class="btn btn-default btn-sm" title="Export to CSV">Export</a>
    </div>
</nav>
@stop
@section('content')
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <table id="user_table" class="table table-bordered">
                    <thead>
                        <tr>
                            <th></th>
                            <th>Name</th>
                            <th>Username</th>
                            <th>Team</th>
                            <th>Designation</th>
                            <th>Region</th>
                            <th>Status</th>
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
    var table = "user_table";
    var token = "<?= csrf_token() ?>";  

    $(function(){
        $('#user_table').DataTable({
            "bProcessing": false,
            "bServerSide": true,
            "autoWidth": false,
            lengthMenu: [
                [ 10, 25, 50, 100,200,500],
                [ '10', '25', '50','100','200','500']
            ],
            "sAjaxSource": '<?= route('systemuser.index')?>',
            "aaSorting": [ 1,"desc"],
            "aoColumns": [
            {   mData:"id",sWidth:"12%",bSortable : true,bVisible : false},
            {   mData:"name",sWidth:"12%",bSortable : true,

            mRender : function(v,t,o){  
                var is_edit_show = <?= App\Helpers\DesignationPermissionCheck::isPermitted('systemuser.edit')?>;
                var is_update_show = <?= App\Helpers\DesignationPermissionCheck::isPermitted('systemuser.update')?>;
                var act_html = v;

                if(is_edit_show != 0 && is_update_show != 0){
                    var edit_path = "<?= route('systemuser.edit',['id'=>':id']) ?>";
                    edit_path = edit_path.replace(':id',o['id']);

                    var act_html  = '<a title="Edit '+o['name']+'" href="'+edit_path+'">'+ v +'</a>'
                }

                return act_html;
            }
        },
        { mData:"email",bSortable : true,sClass : 'text-center',sWidth:"20%",},
        { mData:"team",bSortable : true,sClass : 'text-center',sWidth:"15%" },
        { mData:"designation",bSortable : true,sClass : 'text-center',sWidth:"15%" },
        { mData:"region",bSortable : true,sClass : 'text-center',sWidth:"20%" },
        { mData:"status",bSortable : true,sClass : 'text-center',sWidth:"20%",
            mRender: function(v,t,o){
                if (v == "approve") {
                    var act_html = '<span class="badge badge-success"><?= ucwords('approve')?></span>';
                }else if(v == "pending"){
                    var act_html = '<span class="badge badge-default"><?= ucwords('pending')?></span>';
                }else if(v == "reject"){
                    var act_html = '<span class="badge badge-danger"><?= ucwords('reject')?></span>';
                }
                return act_html;
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
