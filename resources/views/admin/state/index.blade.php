@extends('admin.layout.layout')
@section('style')
    <?=Html::style('backend/plugins/sweetalert/sweetalert.min.css', [], IS_SECURE)?>    
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
        <h4><i class="fa fa-user"></i>State</h4>
    </div>
    <div class="top_filter"></div>
    <div class="pl-10">
    @if(App\Helpers\DesignationPermissionCheck::isPermitted('state.create') && App\Helpers\DesignationPermissionCheck::isPermitted('state.store'))
          <a href="<?= route('state.create') ?>" class="btn btn-primary btn-sm" title="Add STATE" data-toggle="modal">Add STATE</a>
    @endif  
    <a href="<?= route('state.export')?>" class="btn btn-default btn-sm" title="Export to CSV">Export</a>    
    </div>
</nav>
@stop
@section('content')

    <div class="col-md-12">
        <div class="card">
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
                                        <li><a href="javascript:void(0);" id="delete" class="delete-btn">Delete selected user</a></li>
                                    </ul>
                                </div>
                            </li>
                        </ul>
                    </div>
                    <div class="clearfix"></div>
                </div>                
                <div class="p-20">
                    <table class="table" id="user">
                        <thead>
                            <tr>
                                <th>Created At</th>
                            @if(App\Helpers\DesignationPermissionCheck::isPermitted('state.delete'))
                                <th class="select-all no-sort">
                                    <div class="animated-checkbox">
                                        <label class="m-0">
                                            <input type="checkbox" id="checkAll"/>
                                            <span class="label-text"></span>
                                        </label>
                                    </div>
                                </th>
                            @else
                                <th>Id</th>
                            @endif    
                                <th>State</th>
                                
                            </tr>
                        </thead>
                        <tbody></tbody>
                    </table>
                </div>
            </div>
            @include('admin.layout.overlay')
        </div>
    </div>
</div>

<div class="modal fade" id="add_data" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <!--Header-->
            <div class="modal-header bg-primary" style="padding: 10px;">
                <button type="button" class="close"  data-dismiss="modal" aria-label="Close" style="opacity: 1">
                    <span aria-hidden="true" style="color: #ffffff">×</span>
                </button>
                <h4 class="modal-title" id="myModalLabel">User</h4>
            </div>
            
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
<?= Html::script('backend/js/select2.min.js',[],IS_SECURE) ?>

<script type="text/javascript">
var table = "user";
var title = "Are you sure to delete this state?";
var text = "You will not be able to recover this record";
var type = "warning";
var delete_path ="<?=URL::route('state.delete')?>";
var token = "<?=csrf_token()?>";
  
$(function(){
    $('#delete').click(function(){
        var delete_id = $('#'+table+' tbody .checkbox:checked');
        checkLength(delete_id);
    });

    $('#user').DataTable({
        "bProcessing": false,
        "bServerSide": true,
        "autoWidth": false,
        lengthMenu: [
            [ 10, 25, 50, 100,200,500],
            [ '10', '25', '50','100','200','500']
        ],
        "sAjaxSource": '<?=route('state.index')?>',
        "aaSorting": [ 2,"desc"],
        "aoColumns": [

        {   mData:"created_at",sWidth:"80%",bSortable : true,bVisible : false},

        @if(App\Helpers\DesignationPermissionCheck::isPermitted('state.delete'))
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
            {   mData:"title",sWidth:"80%",bSortable : false,bVisible : false},
        @endif             
        {   mData:"title",sWidth:"80%",bSortable : true,
                mRender: function(v, t, o) {
                    var is_edit_show = <?= App\Helpers\DesignationPermissionCheck::isPermitted('state.edit')?>;
                    var is_update_show = <?= App\Helpers\DesignationPermissionCheck::isPermitted('state.update')?>;
                    var act_html = v;

                    if(is_edit_show != 0 && is_update_show != 0){
                        var edit_path = "<?= route('state.edit',['id'=>':id']) ?>";
                        edit_path = edit_path.replace(':id',o['id']);

                        act_html  = '<a title="Edit '+o['title']+'" href="'+edit_path+'">'+ v +'</a>';
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



function userData()
{
    $('#add_data').modal('show');
}

</script>
@include('admin.layout.alert')
@stop