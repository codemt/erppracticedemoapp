@extends('admin.layout.layout')
@section('style')
    <?= Html::style('backend/css/dataranger.css',[],IS_SECURE) ?>
@stop
@section('top_fixed_content')
<nav class="navbar navbar-static-top">
    <div class="title">
        <h4><i class="fa fa-list"></i>Purchase Requisition</h4>
    </div>
    <div style="width: 21%;min-width: 200px;">
        <form id="frm_filter" name ="frm_filter">
            <div class="form-group" style="margin-bottom: 0">
                <div class="input-group date_range">
                    <div class="input-group-addon"><i class="fa fa-fw fa-calendar"></i></div>
                    <input type="text" name="yoy[]" class="form-control pull-right" id="date_range_1">
                </div>
            </div>
        </form>
    </div>
    <div class="top_filter"></div>
    <div class="pl-10">
        <a href="<?= route('purchase-requisition.create') ?>" class="btn btn-primary btn-sm" title="Add New">Add New</a>
        <a href="<?= route('purchase.requisition.export')?>" class="btn btn-default btn-sm" title="Export to CSV">Export</a>
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
                <table id="purchase_requisition_table" class="table table-bordered">
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
                            <th>Company Name</th>
                            <th>Creation Date</th>
                            <th>Approval Date</th>
                            <th>Manufacturer Name</th>
                            <th>Total Price in INR</th>
                            <th>Total Price in USD</th>
                            <th>Purchase Approval Status</th>
                            <th>Po No</th>
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
<?=Html::script('backend/js/delete_script.js', [], IS_SECURE)?>
<?= Html::script('backend/js/moment.min.js',[],IS_SECURE) ?>
<?= Html::script('backend/js/dateranger.js',[],IS_SECURE) ?>
<?= Html::script('backend/js/select2.min.js',[],IS_SECURE) ?>

<script type="text/javascript">
    var table = "purchase_requisition_table";
    var title = "Are you sure to delete this Sales Order?";
    var text = "You will not be able to recover this record";
    var type = "warning";
    var delete_path = "<?= route('addproduct.delete') ?>";
    var token = "<?= csrf_token() ?>";  

    $(function(){
        $('#delete').click(function(){
            var delete_id = $('#'+table+' tbody .checkbox:checked');
            checkLength(delete_id);
        });

        $('#purchase_requisition_table').DataTable({
            "bProcessing": false,
            "bServerSide": true,
            "autoWidth": false,
            lengthMenu: [
                [ 10, 25, 50, 100,200,500],
                [ '10', '25', '50','100','200','500']
            ],
            "sAjaxSource": '<?= route('purchase-requisition.index')?>',
            "fnServerParams": function ( aoData ) {
                var date = $('#date_range_1').val();
                var date_split = date.split('-');
                var fromdate = date_split[0].split("/").reverse().join("-").replace(/\s+/g, "");
                var todate = date_split[1].split("/").reverse().join("-").replace(/\s+/g, "");
                aoData.push({ "name": "fromdate", "value": fromdate });
                aoData.push({ "name": "todate", "value": todate });
            },
            "aaSorting": [2,"desc"],
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
            {   mData:"company_name",sWidth:"15%",bSortable : true,
                mRender : function(v,t,o){  

                    var edit_path = "<?= route('purchase-requisition.edit',['id'=>':id']) ?>";
                    edit_path = edit_path.replace(':id',o['id']);

                    var act_html  = '<a title="Edit '+o['order_date']+'" href="'+edit_path+'">'+ v +'</a>'

                    return act_html;
                }
            },
            { mData:"created_at",bSortable : true,sClass : 'text-center',sWidth:"10%",},
            { mData:"purchase_approval_date",bSortable : true,sClass : 'text-center',sWidth:"10%",},
            { mData:"supplier_name",bSortable : true,sClass : 'text-center',sWidth:"10%" },
            { mData:"total_price",bSortable : true,sClass : 'text-center',sWidth:"15%" },
            { mData:"dollar_total_price",bSortable : true,sClass : 'text-center',sWidth:"10%" },
            { mData:"purchase_approval_status",bSortable : true,sClass : 'text-center',sWidth:"15%",
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
                    }else if(v == "<?= config('Constant.status.waiting for admin')?>"){
                        var act_html = '<span class="badge badge-primary"><?= ucwords(config('Constant.status.waiting for admin'))?></span>';
                    }else if(v == "<?= config('Constant.status.waiting for owner')?>"){
                        var act_html = '<span class="badge badge-default"><?= ucwords(config('Constant.status.waiting for owner'))?></span>';
                    }else if(v == "<?= config('Constant.status.onhold')?>"){
                        var act_html = '<span class="badge badge-danger"><?= ucwords(config('Constant.status.onhold'))?></span>';
                    }
                    return act_html;
                },
            },
            { mData:"po_no",bSortable : true,sClass : 'text-center',sWidth:"15%",
            },
            ],
            fnPreDrawCallback : function() { $("div.overlay").css('display','flex'); },
            fnDrawCallback : function (oSettings) {
                $("div.overlay").hide();
            },
        });
    });   
    
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
            endDate: moment()
        });
        $('#date_range_1').change(function(){
            $('#purchase_requisition_table').each(function() {
                dt = $(this).dataTable();
                dt.fnStandingRedraw();
            });
        });
</script>
@include('admin.layout.alert')
@stop