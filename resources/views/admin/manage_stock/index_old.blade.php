@extends('admin.layout.layout')
@section('top_fixed_content')
<nav class="navbar navbar-static-top">
    <div class="title">
        <h4><i class="fa fa-list"></i>Manage Stock Master</h4>
    </div>
    <div class="top_filter"></div>
    <div style="width: 21%;min-width: 200px;margin-right: 10px">
        <?=Form::select('company_id',$companies, null, array('class' => 'form-control select', 'id' => 'company_id'))?>
    </div>
    <div style="width: 21%;min-width: 200px;margin-right: 10px">
        <?=Form::select('supplier_id',$suppliers, null, array('class' => 'form-control select', 'id' => 'supplier_id'))?>
    </div>
    <div style="width: 21%;min-width: 200px;margin-right: 10px">
        <?=Form::select('qty',[''=>'Select Qty','0'=>'0','1,5'=>'1 to 5','6,10'=>'6 to 10','11,+'=>'11+'], null, array('class' => 'form-control select', 'id' => 'qty'))?>
    </div>
    <div class="pl-10">
        <a href="<?= route('manage_stock.export')?>" class="btn btn-default btn-sm" title="Export to CSV">Export</a>
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
                <table id="managestock_table" class="table table-bordered">
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
                            <th>Name</th>
                            <th>SKU</th>
                            <th>Total Qty</th>
                            <th>Total Physical Qty</th>
                            <th>Total Blocked Qty</th>
                            <th>Company</th>
                            <th>Principle</th>
                            <th>Weight</th>
                            <th>Current Market Price</th>
                            <th>Open PO Qty</th>
                            <th>Open So Qty</th>
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
<?= Html::script('backend/js/select2.min.js',[],IS_SECURE) ?>

<script type="text/javascript">
var table = "managestock_table";
var token = "<?=csrf_token()?>";

$(function(){
    $('#company_id').select2();
    $('#supplier_id').select2();
    $('#qty').select2();
    $('#managestock_table').DataTable({
        "bProcessing": false,
        "bServerSide": true,
        "autoWidth": false,
        lengthMenu: [
            [ 10, 25, 50, 100,200,500],
            [ '10', '25', '50','100','200','500']
        ],
        "sAjaxSource": "<?= route('managestock.index')?>",
        "fnServerParams": function ( aoData ) {
            var company_id = $('#company_id').val();
            var supplier_id = $('#supplier_id').val();
            var qty = ($('#qty').val()).split(',');
            var fromqty = 0;
            var toqty = 0;
            fromqty = qty[0];
            toqty = qty[1];
            aoData.push({ "name": "company_id", "value": company_id });
            aoData.push({ "name": "supplier_id", "value": supplier_id });
            aoData.push({ "name": "fromqty", "value": fromqty });
            aoData.push({ "name": "toqty", "value": toqty });
            server_params = aoData;
        },
        "aaSorting": [ 1,"asc"],
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
        {   mData:"name",sWidth:"16%",bSortable : true},
        { mData:"sku",bSortable : true,sClass : 'text-center',sWidth:"16%"},
        { mData:"total_qty",bSortable : true,sClass : 'text-center',sWidth:"16%"},
        { mData:"total_physical_qty",bSortable : true,sClass : 'text-center',sWidth:"15%" },
        { mData:"total_blocked_qty",bSortable : true,sClass : 'text-center',sWidth:"15%" },
        { mData:"company",bSortable : true,sClass : 'text-center',sWidth:"15%" },
        { mData:"principle",bSortable : true,sClass : 'text-center',sWidth:"15%" },
        { mData:"weight",bSortable : true,sClass : 'text-center',sWidth:"15%" },
        { mData:"market_price",bSortable : true,sClass : 'text-center',sWidth:"15%" },
        { mData:"open_po_qty",bSortable : true,sClass : 'text-center',sWidth:"15%" },
        { mData:"open_so_qty",bSortable : true,sClass : 'text-center',sWidth:"15%" },

        ],
        fnPreDrawCallback : function() { $("div.overlay").css('display','flex'); },
        fnDrawCallback : function (oSettings) {
            $("div.overlay").hide();
        },
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
    $('#qty').change(function(){
        $('.dataTable').each(function() {
            dt = $(this).dataTable();
            dt.fnDraw();
        });
    });
});
</script>
@include('admin.layout.alert')
@stop