@extends('admin.layout.layout')
@section('top_fixed_content')
<nav class="navbar navbar-static-top">
    <div class="title">
        <h4><i class="fa fa-list"></i>Designation Master</h4>
    </div>
    <div class="top_filter"></div>
    <div class="pl-10">
        @if(App\Helpers\DesignationPermissionCheck::isPermitted('designation.create') && App\Helpers\DesignationPermissionCheck::isPermitted('designation.store'))
            <a href="<?= route('designation.create') ?>" class="btn btn-primary btn-sm" title="Add New">Add Designation</a>
        @endif    
        <a href="<?= route('designation.export')?>" class="btn btn-default btn-sm" title="Export to CSV">Export</a>
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
                <table id="designation_table" class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Id</th>
                            <th>Updated At</th>
                            <th>Designation Name</th>
                            <th>Team</th>
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
       $('#designation_table').DataTable({
            "bProcessing": false,
            "bServerSide": true,
            "autoWidth": false,
            lengthMenu: [
                [ 10, 25, 50, 100,200,500],
            [ '10', '25', '50','100','200','500']
            ],
            "sAjaxSource": '<?= route('designation.index')?>',
            "aaSorting": [ 2,"desc"],
            "aoColumns": [
            {   mData:"id",sWidth:"20%",bSortable : true, bVisible : false},
            {   mData:"updated_at",sWidth:"20%",bSortable : true, bVisible : false},
            {   mData:"name",sWidth:"20%",bSortable : true,

                mRender : function(v,t,o){  
                    var is_edit_show = <?= App\Helpers\DesignationPermissionCheck::isPermitted('designation.edit')?>;
                    var is_update_show = <?= App\Helpers\DesignationPermissionCheck::isPermitted('designation.update')?>;
                    var act_html = v;
                    console.log(o);
                    if(is_edit_show != 0 && is_update_show != 0){
                        var edit_path = "<?= route('designation.edit',['id'=>':id']) ?>";
                        edit_path = edit_path.replace(':id',o['id']);

                        var act_html  = '<a title="Edit '+o['name']+'" href="'+edit_path+'">'+ v +'</a>';
                    }

                    return act_html;
                }
            },
            { mData:"team",bSortable : true,sClass : 'text-center',sWidth:"20%" },
            {   mData:"status",sWidth:"5%",bSortable : true,
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