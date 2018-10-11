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
        <h4><i class="fa fa-user"></i>Sales Order</h4>
    </div>
    <div class="top_filter"></div>
    
    @if(App\Helpers\DesignationPermissionCheck::isPermitted('salesorder.create') && App\Helpers\DesignationPermissionCheck::isPermitted('salesorder.store'))
        <div class="pl-10">
            <a href="<?= route('salesorder.create') ?>" class="btn btn-primary btn-sm" title="Add USERS" data-toggle="modal">Add Sales Order</a>
        </div>
    @endif    
    <div class="pl-10">
        <a href="<?= route('salesorder.export')?>" class="btn btn-default btn-sm" title="Export to CSV">Export</a>
    </div>              
</nav>
            
@stop
@section('content')
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="row">
                <div class="col-sm-4 col-md-4 mb-10">
                <?=Form::select('company_id',$companies, null, array('class' => 'form-control select', 'id' => 'company_id'))?>
                </div>
                <div class="col-sm-4 col-md-4 mb-10"> 
                    <?=Form::select('supplier_id',$suppliers, null, array('class' => 'form-control select', 'id' => 'supplier_id'))?>
                </div>
                <div class="col-sm-4 col-md-4 mb-10">
                    <form id="frm_filter" name ="frm_filter">
                        <div class="form-group" style="margin-bottom: 0">
                            <div class="input-group date_range">
                                <div class="input-group-addon"><i class="far fa-calendar-alt"></i></div>
                                <input type="text" name="yoy[]" class="form-control pull-right" id="date_range_1">
                            </div>
                        </div>
                    </form>
                </div>
                
            </div>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="row">
                <table class="col-md-12">
                    <tr style="font-size: 16px;">
                        <td class="col-md-4" style="text-align: left">Total Sale: <b>@if($total_value != null){{$total_value}} @else 0 @endif</b></td>
                        <td class="col-md-4" style="text-align: left">Total Dispatch Sale: <b>12</b></td>
                        @if($is_visiable_team_sale == true)
                        <td class="col-md-4" style="text-align: left">Total Team Sale: <b>@if($total_team_sale_value['grand_total'] != null) {{$total_team_sale_value['grand_total']}} @else 0 @endif</b></td>
                        @endif
                    </tr>
                </table>
            </div>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-body">
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
                <div class=" table-responsive" style="overflow-y: hidden;">
                    <table id="sales_table" class="table table-bordered">
                        <thead>
                            <tr>
                                <th class="select-all no-sort">
                                    <div class="animated-checkbox">
                                        <label class="m-0">
                                            <input type="checkbox" id="checkAll" />
                                            <span class="label-text"></span>
                                        </label>
                                    </div>
                                </th>
                                <th>SO No.</th>
                                <th>SO Date</th>
                                <th>Customer Name</th>
                                <th>Project Name</th>
                                <th>Total Qty</th>
                                <th>Total Value</th>
                                <th>Sales Person Name</th>
                                <th>Status</th>
                                @if(App\Helpers\DesignationPermissionCheck::isPermitted('salesorder.reorder') != 0 && App\Helpers\DesignationPermissionCheck::isPermitted('salesorder.reorderstore') != 0)
                                <th>Action</th>
                                @endif
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
@stop
@section('script')
<?=Html::script('backend/plugins/datatable/jquery.dataTables.min.js', [], IS_SECURE)?>
<?=Html::script('backend/plugins/datatable/dataTables.bootstrap.min.js', [], IS_SECURE)?>
<?=Html::script('backend/plugins/sweetalert/sweetalert.min.js', [], IS_SECURE)?>
<?=Html::script('backend/js/fnstandrow.js', [], IS_SECURE)?>
<?=Html::script('backend/js/delete_script.js', [], IS_SECURE)?>
<?= Html::script('backend/js/moment.min.js',[],IS_SECURE) ?>
<?= Html::script('backend/js/dateranger.js',[],IS_SECURE) ?>
<?= Html::script('backend/js/select2.min.js',[],IS_SECURE) ?>

<script type="text/javascript">
    var table = "sales_table";
    var title = "Are you sure to delete this Sales Order?";
    var text = "You will not be able to recover this record";
    var type = "warning";
    var delete_path = "<?= route('salesorder.delete') ?>";
    var token = "<?= csrf_token() ?>";  
    var is_reorder_show = <?= App\Helpers\DesignationPermissionCheck::isPermitted('salesorder.reorder')?>;
    var is_reorder_store_show = <?= App\Helpers\DesignationPermissionCheck::isPermitted('salesorder.reorderstore')?>;
    
    $(function(){
        $('#company_id').select2();
        $('#supplier_id').select2();
        //date range filter
        $('#date_range_1').daterangepicker({
            ranges: {
            'Today': [moment(), moment()],
            'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
            'Last 7 Days': [moment().subtract(6, 'days'), moment()],
            'Last 30 Days': [moment().subtract(29, 'days'), moment()],
            'This Month': [moment().startOf('month'), moment().endOf('month')],
            'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')],
            'This Year': [moment().startOf('year').startOf('days'),moment()],
            'Last Year': [moment().subtract('month',12).startOf('days'), moment().subtract(moment(),12).endOf('days')]
          },
          opens : "right",
          startDate: moment().startOf('year').startOf('days'),
          endDate: moment(),
        });

        $('#delete').click(function(){
            var delete_id = $('#'+table+' tbody .checkbox:checked');
            checkLength(delete_id);
        });

        $('#sales_table').DataTable({
            "bProcessing": false,
            "bServerSide": true,
            "autoWidth": false,
            lengthMenu: [
                [ 10, 25, 50, 100,200,500],
                [ '10', '25', '50','100','200','500']
            ],
            "sAjaxSource": '<?= route('salesorder.index')?>',
            "fnServerParams": function ( aoData ) {
                var date = $('#date_range_1').val();
                var company_id = $('#company_id').val();
                var supplier_id = $('#supplier_id').val();
                var date_split = date.split('-');
                var fromdate = date_split[0].split("/").reverse().join("-").replace(/\s+/g, "");
                var todate = date_split[1].split("/").reverse().join("-").replace(/\s+/g, "");
                aoData.push({ "name": "todate", "value": todate });
                aoData.push({ "name": "fromdate", "value": fromdate });
                aoData.push({ "name": "company_id", "value": company_id });
                aoData.push({ "name": "supplier_id", "value": supplier_id });
            server_params = aoData;
            },
            "aaSorting": [ 2,"desc"],
            "aoColumns": [
            {   
                mData: "id",
                bSortable:false,
                bVisible:false,
                sWidth:"2%",
                sClass:"text-center",
                mRender: function (v, t, o) {
                    return '<div class="animated-checkbox"><label class="m-0"><input class="checkbox" type="checkbox" id="chk_'+v+'" name="special_id['+v+']" value="'+v+'"/><span class="label-text"></span></label></div>';
                },
            },
            {   mData:"so_no",sWidth:"15%",bSortable : true,

                    mRender : function(v,t,o){  
                        var is_edit_show = <?= App\Helpers\DesignationPermissionCheck::isPermitted('salesorder.edit')?>;
                        var is_update_show = <?= App\Helpers\DesignationPermissionCheck::isPermitted('salesorder.update')?>;
                        var act_html = v;

                        if(is_edit_show != 0 && is_update_show != 0){
                            var edit_path = "<?= route('salesorder.edit',['id'=>':id']) ?>";
                            edit_path = edit_path.replace(':id',o['id']);

                            act_html  = '<a title="Edit '+o['so_no']+'" href="'+edit_path+'">'+ v +'</a>'
                        }

                        return act_html;
                    }
            },
            { mData:"created_at",bSortable : true,sClass : 'text-center',sWidth:"10%",},
            { mData:"customer_name",bSortable : true,sClass : 'text-center',sWidth:"10%",},
            { mData:"project_name",bSortable : true,sClass : 'text-center',sWidth:"10%" },
            { mData:"total_qty",bSortable : true,sClass : 'text-center',sWidth:"15%" },
            { mData:"total_value",bSortable : true,sClass : 'text-center',sWidth:"10%" },
             { mData:"sales_person_name",bSortable : true,sClass : 'text-center',sWidth:"15%" },
            { mData:"status",bSortable : true,sClass : 'text-center',sWidth:"15%",
                mRender: function(v,t,o){
                    if (v == "<?= config('Constant.status.approve')?>") {
                        var act_html = '<span class="badge badge-success"><?= ucwords(config('Constant.status.approve'))?></span>';
                    }else if(v == "<?= config('Constant.status.pending')?>"){
                        var act_html = '<span class="badge badge-danger"><?= ucwords(config('Constant.status.pending'))?></span>';
                    }else if(v == "<?= config('Constant.status.ammended approve')?>"){
                        var act_html = '<span class="badge badge-info"><?= ucwords(config('Constant.status.ammended approve'))?></span>';
                    }else if(v == "<?= config('Constant.status.received')?>"){
                        var act_html = '<span class="badge badge-default"><?= ucwords(config('Constant.status.received'))?></span>';
                    }else if(v == "<?= config('Constant.status.waiting for approval')?>"){
                        var act_html = '<span class="badge badge-warning"><?= ucwords(config('Constant.status.waiting for approval'))?></span>';
                    }else if(v == "<?= config('Constant.status.waiting for accountant')?>"){
                        var act_html = '<span class="badge badge-primary"><?= ucwords(config('Constant.status.waiting for accountant'))?></span>';
                    }else if(v == "<?= config('Constant.status.waiting for owner')?>"){
                        var act_html = '<span class="badge badge-default"><?= ucwords(config('Constant.status.waiting for owner'))?></span>';
                    }else if(v == "<?= config('Constant.status.onhold')?>"){
                        var act_html = '<span class="badge badge-danger"><?= ucwords(config('Constant.status.onhold'))?></span>';
                    }
                    return act_html;
                },
            },
            @if(App\Helpers\DesignationPermissionCheck::isPermitted('salesorder.reorder') != 0 && App\Helpers\DesignationPermissionCheck::isPermitted('salesorder.reorderstore') != 0)
                {
                    mData: 'null',
                    bSortable: false,
                    sWidth: "18px",
                    sClass: "text-center",
                    mRender: function(v, t, o) {
                        var id= o['id'];
                        var reorder_path = "<?=URL::route('salesorder.reorder', ['id' => ':id'])?>";
                        reorder_path = reorder_path.replace(':id',o['id']);
                        var act_html = "<div class='btn-group'>"
                            +"<a href='"+reorder_path+"' data-toggle='tooltip' title='Reorder' data-placement='top' class='btn btn-xs btn-info p-5' style='font-size:17px; line-height:23px; padding: 4px'><i class='fa fa-fw fa-copy'></i></a>"
                            +"</div>";
                        return act_html;
                    },
                },
            @endif
            ],
            fnPreDrawCallback : function() { $("div.overlay").css('display','flex'); },
            fnDrawCallback : function (oSettings) {
                $("div.overlay").hide();
            },
        });
        
        $('#date_range_1').change(function(){
            $('.dataTable').each(function() {
                dt = $(this).dataTable();
                dt.fnDraw();
            });
        });
        $('#company_id').change(function(){
            $('.dataTable').each(function() {
                dt = $(this).dataTable();
                dt.fnDraw();
            });
        });
        $('#supplier_id').change(function(){
            $('.dataTable').each(function() {
                dt = $(this).dataTable();
                dt.fnDraw();
            });
        });
    });

</script>
@include('admin.layout.alert')
@stop