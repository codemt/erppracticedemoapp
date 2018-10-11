@extends('admin.layout.layout')
@section('top_fixed_content')
<nav class="navbar navbar-static-top">
    <div class="title">
        <h4><i class="fa fa-list"></i>Distributor Master</h4>
    </div>
    <div class="top_filter"></div>
    <div class="pl-10">
        @if(App\Helpers\DesignationPermissionCheck::isPermitted('distributor.create') && App\Helpers\DesignationPermissionCheck::isPermitted('distributor.store'))
            <a href="<?= route('distributor.create') ?>" class="btn btn-primary btn-sm" title="Add New">Add Distributor</a>
        @endif
        <a href="<?= route('distributor.export')?>" class="btn btn-default btn-sm" title="Export to CSV">Export</a>
    </div>
</nav>
@stop
@section('content')
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-body table-responsive">
                <div class="text-right">
                    <div class="number-delete">
                        <ul>
                            <li>
                                <p class="mb-0"><span class="num_item"></span>Item Selected.</p>
                            </li>
                            <li class="bulk-dropdown">
                                <a href="javascript:;">Bulk actions<span class="caret"></span></a>
                                <div class="bulk-box">
                                    <div class="bulk-tooltip"></div>
                                    <ul class="bulk-list">
                                        <li><a href="javascript:void(0);" id="delete" class="delete-btn">Delete selected record</a></li>
                                    </ul>
                                </div>
                            </li>
                        </ul>
                    </div>
                    <div class="clearfix"></div>
                </div>
                <table id="distributor_table" class="table table-bordered">
                    <thead>
                        <tr>
                        @if(App\Helpers\DesignationPermissionCheck::isPermitted('distributor.delete'))
                            <th class="select-all no-sort">
                                <div class="animated-checkbox">
                                    <label class="m-0">
                                        <input type="checkbox" id="checkAll" />
                                        <span class="label-text"></span>
                                    </label>
                                </div>
                            </th>
                        @endif    
                            <th>Distributor Name</th>
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
var table = "distributor_table";
var title = "Are you sure to delete this Distributor?";
var text = "You will not be able to recover this record";
var type = "warning";
var delete_path = "<?= route('distributor.delete') ?>";
var token = "<?=csrf_token()?>";

$(function(){
    $('#delete').click(function(){
        var delete_id = $('#'+table+' tbody .checkbox:checked');
        checkLength(delete_id);
    });

    $('#distributor_table').DataTable({
        "bProcessing": false,
        "bServerSide": true,
        "autoWidth": false,
        lengthMenu: [
            [ 10, 25, 50, 100,200,500],
            [ '10', '25', '50','100','200','500']
        ],
        "sAjaxSource": "<?= route('distributor.index')?>",
        "aaSorting": [ 1,"asc"],
        "aoColumns": [
        @if(App\Helpers\DesignationPermissionCheck::isPermitted('distributor.delete'))
            {   
                mData: "id",
                bSortable:false,
                sWidth:"2%",
                sClass:"text-center",
                mRender: function (v, t, o) {
                    return '<div class="animated-checkbox"><label class="m-0"><input class="checkbox" type="checkbox" id="chk_'+v+'" name="special_id['+v+']" value="'+v+'"/><span class="label-text"></span></label></div>';
                },
            },
        @else
            {   mData:"id",sWidth:"16%",bSortable : false,bVisible : false},
        @endif    
            {   mData:"distributor_name",sWidth:"20%",bSortable : true,

            mRender : function(v,t,o){  
                var is_edit_show = <?= App\Helpers\DesignationPermissionCheck::isPermitted('distributor.edit')?>;
                var is_update_show = <?= App\Helpers\DesignationPermissionCheck::isPermitted('distributor.update')?>;
                var act_html = v;

                if(is_edit_show != 0 && is_update_show != 0){
                    var edit_path = "<?= route('distributor.edit',['id'=>':id']) ?>";
                    edit_path = edit_path.replace(':id',o['id']);

                    var act_html  = '<a title="Edit '+v+'" href="'+edit_path+'">'+ v +'</a>';
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