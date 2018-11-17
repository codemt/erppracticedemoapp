@extends('admin.layout.layout')
@section('style')
    <?= Html::style('backend/css/dataranger.css',[],IS_SECURE) ?>
@stop
@section('top_fixed_content')
<nav class="navbar navbar-static-top">
    <div class="title">
        <h4><i class="fa fa-list"></i>Product Master</h4>
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
        @if(App\Helpers\DesignationPermissionCheck::isPermitted('product.create'))
            <a href="<?= route('product.create') ?>" class="btn btn-primary btn-sm" title="Add New">Add New</a>
        @endif
        <a href="<?= route('product.xml','1') ?>" class="btn btn-primary btn-sm" title="Export To Xml">Stellar Export To Xml</a>
        <a href="<?= route('product.xml','2') ?>" class="btn btn-primary btn-sm" title="Export To Xml">Triton Export To Xml</a>
        @if(App\Helpers\DesignationPermissionCheck::isPermitted('product.export'))
        <a href="<?= route('product.export')?>" class="btn btn-default btn-sm" title="Export to CSV">Export</a>
        @endif
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
                <table id="product_master_table" class="table table-hover">
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
                            <th>Manufacturer Name</th>
                            <th>Product Type</th>
                            <th>Model No</th>
                            <th>Price</th>
                            <th>Max Discount</th>
                            <th>Tax</th>
                            <th>QTY</th>
                            <th>Minimum QTY</th>
                            <th>Product Status</th>
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

<?= Html::script('backend/js/moment.min.js',[],IS_SECURE) ?>
<?= Html::script('backend/js/dateranger.js',[],IS_SECURE) ?>
<?=Html::script('backend/plugins/datatable/jquery.dataTables.min.js', [], IS_SECURE)?>
<?=Html::script('backend/plugins/datatable/dataTables.bootstrap.min.js', [], IS_SECURE)?>
<?=Html::script('backend/plugins/sweetalert/sweetalert.min.js', [], IS_SECURE)?>
<?=Html::script('backend/js/fnstandrow.js', [], IS_SECURE)?>
<?=Html::script('backend/js/delete_script.js', [], IS_SECURE)?>

<script type="text/javascript">
var table = "product_master_table";
var title = "Are you sure to delete this User?";
var text = "You will not be able to recover this record";
var type = "warning";
var delete_path = "<?= route('addproduct.delete') ?>";
var token = "<?=csrf_token()?>";

$(function(){
    $('#delete').click(function(){
        var delete_id = $('#'+table+' tbody .checkbox:checked');
        checkLength(delete_id);
    });

    $('#product_master_table').DataTable({
        "bProcessing": false,
        "bServerSide": true,
        "autoWidth": false,
        lengthMenu: [
            [ 10, 25, 50, -1 ],
            [ '10', '25', '50', 'Show all' ]
        ],
        "sAjaxSource": "<?= route('product.index')?>",
            "fnServerParams": function ( aoData ) {
                var date = $('#date_range_1').val();
                // var newdate = date.split("-").reverse().join("-");
                // console.log(newdate);
                var date_split = date.split('-');
                var fromdate = date_split[0].split("/").reverse().join("-").replace(/\s+/g, "");
                var todate = date_split[1].split("/").reverse().join("-").replace(/\s+/g, "");
                // console.log(fromdate);
                aoData.push({ "name": "fromdate", "value": fromdate });
                aoData.push({ "name": "todate", "value": todate });
            },
        "aaSorting": [ 1,"asc"],
        "aoColumns": [
            {   
                mData: "id",
                bSortable:false,
                sWidth:"2%",
                bVisible : false,
                sClass:"text-center",
                mRender: function (v, t, o) {
                    return '<div class="animated-checkbox"><label class="m-0"><input class="checkbox" type="checkbox" id="chk_'+v+'" name="special_id['+v+']" value="'+v+'"/><span class="label-text"></span></label></div>';
                },
            },
            {   mData:"company_id",sWidth:"25%",bSortable : true, bVisible : true,sClass : 'text-center',sWidth:"25%",
                 mRender : function(v,t,o){  
                    var company_id = o.id.toString();
                    console.log(company_id);
                     console.log(o);
                    var company_name = '';
                    if(o['id'] == 1){
                        company_name = o['company_name'];
                    }
                    if(o['id'] == 2){
                       company_name = o['company_name'];
                    }
                    if(company_id.length == 3){
                        company_name = 'Stellar Ecoenergy Solutions LLP' + ',' + 'Triton Process Automation Pvt Ltd';
                    }
                    var is_check = "<?= App\Helpers\DesignationPermissionCheck::isPermitted('product.edit') && App\Helpers\DesignationPermissionCheck::isPermitted('product.update')?>";
                    if(is_check != 0){
                        var edit_path = "<?= route('product.edit',['id'=>':id']) ?>";
                        edit_path = edit_path.replace(':id',o['id']);
                    
                        var act_html  = '<a title="Edit '+company_name+'" href="'+edit_path+'">'+ company_name +'</a>'
                    }
                    else{
                        var act_html  = '<a title="Edit '+company_name+'">'+ company_name +'</a>'
                    }
                    return act_html;
                }
            },
            { mData:"supplier_name",bSortable : true,sClass : 'text-center',sWidth:"15%"},
            { mData:"product_type",bSortable : true,sClass : 'text-center',sWidth:"5%"},
            { mData:"model_no",bSortable : true,sClass : 'text-center',sWidth:"5%" },
            { mData:"price",bSortable : true,sClass : 'text-center',sWidth:"5%" },
            { mData:"max_discount",bSortable : true,sClass : 'text-center',sWidth:"5%" },
            { mData:"tax",bSortable : true,sClass : 'text-center',sWidth:"5%" ,
                 mRender: function(v,t,o){
                    var act_html = v + "%";
                    return act_html;},
            },
            { mData:"qty",bSortable : true,sClass : 'text-center',sWidth:"5%" },
            { mData:"min_qty",bSortable : true,sClass : 'text-center',sWidth:"5%" },
            { mData:"product_status",bSortable : false,sClass : 'text-center',sWidth:"5%",
                mRender: function(v,t,o){
                    if (v == '1') {
                        var act_html = '<span class="badge badge-success">Enable</span>';
                    }else{
                        var act_html = '<span class="badge badge-danger">Disable</span>';
                    }
                    return act_html;},
            },
            { mData:"company_name",bSortable : true,sClass : 'text-center',sWidth:"5%",bVisible : true },
            { mData:"id",bSortable : true,sClass : 'text-center',sWidth:"5%", bVisible : true },

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
    $('#product_master_table').each(function() {
        dt = $(this).dataTable();
        dt.fnStandingRedraw();
    });
});

</script>
@include('admin.layout.alert')
@stop