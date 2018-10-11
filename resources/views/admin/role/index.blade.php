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
        <h4><i class="fa fa-user"></i>Role Master</h4>
    </div>
    <div class="top_filter"></div>
    <div class="pl-10">
          <a href="<?= route('role.create') ?>" class="btn btn-primary btn-sm" title="Add Role" data-toggle="modal">Role Master</a>
    </div>
</nav>
@stop
@section('content')
<!-- <div class="row">
    <div class="col-md-12">
        <div class="card p-0">
            <div class="card-body table-responsive">
                <div class="p-20">
                    <form id="frm_filter" name ="frm_filter">
                        <div class="form-group row">
                            <div class="col-md-3">
                                <div class="form-group">
                                    <div class="input-group ">
                                        <div class="input-group-addon"><i class="fa fa-fw fa-calendar"></i></div>
                                        <input type="text" name="yoy[]" class="form-control pull-right" id="date_range_1">
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <div class="col-md-12">
                                        <?=Form::text('name', null, ['class' => 'form-control', 'placeholder' => 'Enter field']);?>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="col-md-3">
                                <div class="form-group">
                                    <div class="col-md-12">
                                        <?= Form::select('name[]',['all'=>'All','single'=>'Single','double'=>'Double'], old('status'),array('class' => 'form-control select2','id'=>'order_status_change','multiple'=>true)) ?>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <div class="col-md-12">
                                        <button type="button" class="btn btn-primary filter" title="Filter"><i class="fa fa-filter"></i> Filter</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
 -->
    <div class="col-md-12">
        <div class="card">
            <div class="card-body table-responsive">
                <!-- <ul class="nav nav-tabs">
                    <li class="@if(Request::get('type') == 'active') active @endif">
                        <a href="<?=route('users.index',['type'=>'active'])?>">Active</a>
                    </li>
                    <li  class="@if(Request::get('type') == 'in-active') active @endif">
                        <a href="<?=route('users.index',['type'=>'in-active'])?>">Inactive</a>
                    </li>
                </ul> -->
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
                                <th class="select-all no-sort">
                                    <div class="animated-checkbox">
                                        <label class="m-0">
                                            <input type="checkbox" id="checkAll"/>
                                            <span class="label-text"></span>
                                        </label>
                                    </div>
                                </th>
                                <th>Name</th>
                                <th>Active</th>
                                <th>Action</th>
                                
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

<!-- for user detail show in model -->

<!-- <div id="add_data" class="modal fade">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="m-0 header_title">User Details</h4>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="row col-md-6">
                        <div class="text-primary col-md-4">
                            FirstName:
                        </div>
                        <div class="col-md-8" id="u_firstname">
                            Abc
                        </div>
                    </div>
                    <div class="row col-md-6">
                        <div class="text-primary col-md-4">
                            LastName:
                        </div>
                        <div class="col-md-8" id="u_lastname">Last Name
                        </div>
                    </div>
                </div><hr>
                <div class="row">
                    <div class="row col-md-6">
                        <div class="text-primary col-md-4">
                            Email:
                        </div>
                        <div class="col-md-8" id="u_gmail">abc@gmail.com
                        </div>
                    </div>
                    <div class="row col-md-6">
                        <div class="text-primary col-md-4">
                            Address:
                        </div>
                        <div class="col-md-8" id="u_address">Ahmedabad
                        </div>
                    </div>
                </div><hr>
                <div class="row">
                    <div class="row col-md-6">
                        <div class="text-primary col-md-4">
                            Salary:
                        </div>
                        <div class="col-md-8" id="u_salary">10000
                        </div>
                    </div>
                    <div class=" row col-md-6">
                        <div class="text-primary col-md-4">
                            Department:
                        </div>
                        <div class="col-md-8" id="u_Tester">Tester
                        </div>
                    </div>
                </div>                
            </div>
        </div>
    </div>
</div> -->



<div class="modal fade" id="add_data" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <!--Header-->
            <div class="modal-header bg-primary" style="padding: 10px;">
                <button type="button" class="close"  data-dismiss="modal" aria-label="Close" style="opacity: 1">
                    <span aria-hidden="true" style="color: #ffffff">×</span>
                </button>
                <h4 class="modal-title" id="myModalLabel">Role Master</h4>
            </div>
            <!--Body-->
            <!-- <div class="modal-body">
                <table class="table table-hover">
                        <tr>
                            <td class="text-primary" style="border-top: medium none;">FirstName:</td>
                            <td style="border-top: medium none;">ABD</td>
                            <td class="text-primary" style="border-top: medium none;">LastName:</td>
                            <td style="border-top: medium none;">XYZ</td>
                        </tr>
                        <tr>
                            <td class="text-primary">email:</td>
                            <td>abc@gmail.com</td>
                            <td class="text-primary">Address:</td>
                            <td>Ahmedabad</td>
                        </tr>
                        <tr>
                            <td class="text-primary">Salary:</td>
                            <td>10000</td>
                            <td class="text-primary">Department:</td>
                            <td>PHP</td>
                        </tr>
                </table>
            </div> -->
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
var title = "Are you sure to delete this name?";
var text = "You will not be able to recover this record";
var type = "warning";
var delete_path ="<?=URL::route('role.delete')?>";
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
        "sAjaxSource": '<?=route('role.index')?>',
        "aaSorting": [ 1,"asc"],
        "aoColumns": [
            {   
                mData: "id",
                bSortable:false,
                sWidth:"2%",
                sClass:"text-center",
                mRender: function (v, t, o) {
                    return '<div class="animated-checkbox"><label class="m-0"><input class="checkbox" type="checkbox" id="chk_'+v+'" name="special_id['+v+']" value="'+v+'"/><span class="label-text"></span></label></div>';
                },
            },
            {   mData:"name",sWidth:"40%",bSortable : true,
                mRender: function(v, t, o) {
                    return "<a onclick='userData()'  title='view detail'>"+ o['name']+"</a>";
                },

            },
            
            {   mData:"status",sWidth:"40%",bSortable : true,
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
            
            
            
            {
                mData: 'null',
                bSortable: false,
                sWidth: "18px",
                sClass: "text-center",
                mRender: function(v, t, o) {
                    var id= o['id'];
                    var edit_path = "<?=URL::route('role.edit', ['id' => ':id'])?>";
                    edit_path = edit_path.replace(':id',o['id']);
                    var act_html = "<div class='btn-group'>"
                        +"<a href='"+edit_path+"' data-toggle='tooltip' title='Edit' data-placement='top' class='btn btn-xs btn-info p-5' style='font-size:10px; line-height:15px; padding: 6px'><i class='fa fa-fw fa-edit'></i></a>"
                        + "<a href='javascript:void(0);' onclick=\"deleteRecord('"+delete_path+"','"+title+"','"+text+"','"+token+"','"+table+"','"+type+"',"+id+")\" data-toggle='tooltip' title='Delete' data-placement='top' class='btn btn-xs btn-danger p-5' style='font-size:10px; line-height:15px; padding: 6px'><i class='fa fa-fw fa-trash'></i></a>"
                        +"</div>";
                    return act_html;
                },
            }, 
            
        ],
        fnPreDrawCallback : function() { $("div.overlay").css('display','flex'); },
        fnDrawCallback : function (oSettings) {
            $("div.overlay").hide();
        },
    });
    // $.fn.dataTable.ext.errMode = 'none';
    // $.fn.dataTable.ext.errMode = 'throw';
});



function userData()
{
    $('#add_data').modal('show');
}


</script>
@include('admin.layout.alert')
@stop