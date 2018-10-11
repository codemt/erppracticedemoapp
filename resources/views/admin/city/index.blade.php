@extends('admin.layout.layout')
@section('style')
    <?=Html::style('backend/plugins/sweetalert/sweetalert.min.css', [], IS_SECURE)?>
    <?= Html::style('backend/css/dataranger.css',[],IS_SECURE) ?>
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
        <h4><i class="fa fa-user"></i>City</h4>
    </div>
    <div class="pl-10">
        @if(App\Helpers\DesignationPermissionCheck::isPermitted('cities.create') && App\Helpers\DesignationPermissionCheck::isPermitted('cities.store'))
            <a href="<?= route('cities.create') ?>" class="btn btn-primary btn-sm" title="Add CITY" data-toggle="modal">Add CITY</a>
        @endif  
    </div>
    <div class="pl-10">
        <a href="<?= route('cities.export')?>" class="btn btn-default btn-sm" title="Export to CSV">Export</a>
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
                                <th></th>
                            @if(App\Helpers\DesignationPermissionCheck::isPermitted('cities.delete'))
                                <th class="select-all no-sort">
                                    <div class="animated-checkbox">
                                        <label class="m-0">
                                            <input type="checkbox" id="checkAll"/>
                                            <span class="label-text"></span>
                                        </label>
                                    </div>
                                </th>
                            @else
                                <th></th>
                            @endif    
                                <th>City</th>
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
<?= Html::script('backend/js/moment.min.js',[],IS_SECURE) ?>
<?= Html::script('backend/js/dateranger.js',[],IS_SECURE) ?>

<script type="text/javascript">
var table = "user";
var title = "Are you sure to delete this City?";
var text = "You will not be able to recover this record";
var type = "warning";
var delete_path ="<?=URL::route('cities.delete')?>";
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
        "sAjaxSource": '<?=route('cities.index')?>',
        "aaSorting": [ 2,"desc"],
        "aoColumns": [
            {
                mData:"updated_at",
                bVisible:false,
            },
        @if(App\Helpers\DesignationPermissionCheck::isPermitted('cities.delete'))
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
            {   mData:"title",sWidth:"40%",bSortable : false,bVisible : false},
        @endif    
            {   mData:"title",sWidth:"40%",bSortable : true,
                mRender: function(v, t, o) {
                    var is_edit_show = <?= App\Helpers\DesignationPermissionCheck::isPermitted('cities.edit')?>;
                    var is_update_show = <?= App\Helpers\DesignationPermissionCheck::isPermitted('cities.update')?>;
                    var act_html = v;

                    if(is_edit_show != 0 && is_update_show != 0){
                        var edit_path = "<?= route('cities.edit',['id'=>':id']) ?>";
                        edit_path = edit_path.replace(':id',o['id']);

                        act_html  = '<a title="Edit '+o['title']+'" href="'+edit_path+'">'+ v +'</a>';
                    }

                    return act_html;
                },
            },
            {mData:"state_name",sWidth:"40%",bSortable : true},
            
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