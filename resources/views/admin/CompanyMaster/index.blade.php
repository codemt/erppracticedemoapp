@extends('admin.layout.layout')
@section('top_fixed_content')
<nav class="navbar navbar-static-top">
    <div class="title">
        <h4><i class="fa fa-list"></i>Company Master</h4>
    </div>
    <div class="top_filter"></div>
    <div class="pl-10">
        <a href="<?= route('company.export')?>" class="btn btn-default btn-sm" title="Export to CSV">Export</a>
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
                <table id="company_table" class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Id</th>
                            <th>Company Name</th>
                            <th>SPOC Name</th>
                            <th>SPOC Email</th>
                            <th>SPOC Phone</th>
                            <th>Bank Name</th>
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
<!-- for error list show in model -->
@section('script')

<?=Html::script('backend/plugins/datatable/jquery.dataTables.min.js', [], IS_SECURE)?>
<?=Html::script('backend/plugins/datatable/dataTables.bootstrap.min.js', [], IS_SECURE)?>
<?=Html::script('backend/plugins/sweetalert/sweetalert.min.js', [], IS_SECURE)?>
<?=Html::script('backend/js/fnstandrow.js', [], IS_SECURE)?>
<?=Html::script('backend/js/delete_script.js', [], IS_SECURE)?>

<script type="text/javascript">
var table = "company_table";
var token = "<?=csrf_token()?>";

$(function(){
    $('#delete').click(function(){
        var delete_id = $('#'+table+' tbody .checkbox:checked');
        checkLength(delete_id);
    });

    $('#company_table').DataTable({
        "bProcessing": false,
        "bServerSide": true,
        "autoWidth": false,
        lengthMenu: [
            [ 10, 25, 50, 100,200,500],
            [ '10', '25', '50','100','200','500']
        ],
        "sAjaxSource": "<?= route('companymaster.index')?>",
        "aaSorting": [ 1,"asc"],
        "aoColumns": [
            {   mData:"id",sWidth:"16%",bSortable : true,bVisible : false},
            {   mData:"company_name",sWidth:"16%",bSortable : true,

            mRender : function(v,t,o){  
                var is_edit_show = <?= App\Helpers\DesignationPermissionCheck::isPermitted('companymaster.edit')?>;
                var is_update_show = <?= App\Helpers\DesignationPermissionCheck::isPermitted('companymaster.update')?>;
                var act_html = v;

                if(is_edit_show != 0 && is_update_show != 0){
                    var edit_path = "<?= route('companymaster.edit',['id'=>':id']) ?>";
                    edit_path = edit_path.replace(':id',o['id']);

                    var act_html  = '<a title="Edit '+o['company_name']+'" href="'+edit_path+'">'+ v +'</a>';
                }

                return act_html;
            }
        },
        { mData:"spoc_name",bSortable : true,sClass : 'text-center',sWidth:"16%"},
        { mData:"spoc_email",bSortable : true,sClass : 'text-center',sWidth:"16%"},
        { mData:"spoc_phone",bSortable : true,sClass : 'text-center',sWidth:"15%" },
        { mData:"bankname",bSortable : true,sClass : 'text-center',sWidth:"15%" },

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
