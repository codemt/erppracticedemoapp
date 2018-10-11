@extends('admin.layout.layout')
@section('top_fixed_content')
<nav class="navbar navbar-static-top">
    <div class="title">
        <h4><i class="fa fa-list"></i>Manage Stock Master</h4>
    </div>
    <div class="top_filter"></div>
</nav>
@stop
@section('content')
<div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="row">
                    <div style="width: 21%;min-width: 200px;margin-right: 10px;margin-top: 15px;margin-bottom: 10px" class="col-sm-3 col-md-3 mb-10">
                        <?=Form::select('company_id',$companies, null, array('class' => 'form-control select_2 select company', 'id' => 'company_id'))?>
                        <span class="help-inline text-danger" id="company_error">&nbsp;</span>
                    </div>
                    <div style="width: 21%;min-width: 200px;margin-right: 10px;margin-top: 15px;margin-bottom: 10px" class="col-sm-3 col-md-3">
                        <?=Form::select('supplier_id',$suppliers, null, array('class' => 'form-control select_2 select supplier', 'id' => 'supplier_id'))?>
                        <span class="help-inline text-danger" id="spplier_error">&nbsp;</span>
                    </div>
                    <div style="width: 21%;min-width: 200px;margin-right: 10px;margin-top: 15px;margin-bottom: 10px" class="col-sm-3 col-md-3">
                        <?=Form::select('qty',[''=>'Select Qty','0'=>'0','1,5'=>'1 to 5','6,10'=>'6 to 10','11,+'=>'11+'], null, array('class' => 'form-control select_2 select ', 'id' => 'qty'))?>
                        <span class="help-inline text-danger" >&nbsp;</span>
                    </div>
                    <div class="col-md-1" style="margin-top: 20px" id="export_button">
                        <a href="<?= route('manage_stock.export')?>" class="btn btn-default btn-sm" title="Export to CSV">Export</a>
                    </div>
                    @if(App\Helpers\DesignationPermissionCheck::isPermitted('manage_stock.generateporesponse'))
                        <div class="col-md-2" style="margin-top: 20px">
                            <a class="btn btn-default btn-sm" title="Export to CSV" id="generate_po">Generate PO</a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    <div class="col-md-12">
        <div class="card">
            <div class="card-body table-responsive">
                <div class="text-right">
                    <div class="number-delete">
                        <!-- <ul>
                            <li>
                                <p class="mb-0"><span class="num_item"></span>Item Selected.</p>
                            </li>
                            <li class="bulk-dropdown">
                                <a href="javascript:;">Bulk actions<span class="caret"></span></a>
                                <div class="bulk-box">
                                    <div class="bulk-tooltip"></div>
                                    <ul class="bulk-list">
                                        <li><a href="" id="" class="">Delete selected record</a></li>
                                    </ul>
                                </div>
                            </li>
                        </ul> -->
                    </div>
                    <div class="clearfix"></div>
                </div>
                <table id="managestock_table" class="table table-bordered">
                    <thead>
                        <tr>
                            @if(App\Helpers\DesignationPermissionCheck::isPermitted('manage_stock.generateporesponse'))
                                <th class="select-all no-sort">
                                    <div class="animated-checkbox">
                                        <label class="m-0">
                                            <input type="checkbox" id="checkAll" onclick="DisplayAllerrorMsg()" />
                                            <span class="label-text"></span>
                                        </label>
                                    </div>
                                </th>
                            @endif
                            <th>Name</th>
                            <th>SKU</th>
                            <th>Weight</th>
                            <th>Total Qty</th>
                            @if(App\Helpers\DesignationPermissionCheck::isPermitted('manage_stock.generateporesponse'))
                            <th>Enter Qty</th>
                            @endif
                            <th>Total Blocked Qty</th>
                            <th>Open So Qty</th>
                            <th>Open PO Qty</th>
                            <th>Current Market Price</th>
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
<?= Html::script('backend/js/jquery.jeditable.min.js',[],IS_SECURE) ?>

<script type="text/javascript">
var table = "managestock_table";
var token = "<?=csrf_token()?>";
var master;
$(function(){
    $('#company_id').select2();
    $('#supplier_id').select2();
    $('#qty').select2();
    master = $('#managestock_table').dataTable({
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
        "aaSorting": [ @if(App\Helpers\DesignationPermissionCheck::isPermitted('manage_stock.generateporesponse')) 1 @else 0 @endif,"asc"],
        "aoColumns": [
            
            @if(App\Helpers\DesignationPermissionCheck::isPermitted('manage_stock.generateporesponse'))
            {   
                mData: "id",
                bSortable:false,
                bVisible:true,
                sWidth:"2%",
                sClass:"text-center",
                mRender: function (v, t, o) {
                    return '<div class="animated-checkbox"><label class="m-0"><input class="checkbox" type="checkbox" id="chk_'+v+'" name="special_id['+v+']" value="'+v+'"/><span class="label-text" onclick="DisplayerrorMsg('+v+')"></span></label></div>';
                },
            },
            @endif
        {   mData:"name",sWidth:"16%",bSortable : true},
        { mData:"sku",bSortable : true,sClass : 'text-center',sWidth:"16%"},
        { mData:"weight",bSortable : true,sClass : 'text-center',sWidth:"15%" },
        { mData:"total_qty",bSortable : true,sClass : 'text-center',sWidth:"16%"},
        @if(App\Helpers\DesignationPermissionCheck::isPermitted('manage_stock.generateporesponse'))
        { mData:"po_qty",bSortable : true,sClass : 'text-center qty_text',sWidth:"15%" ,
        },
        @endif
        { mData:"total_blocked_qty",bSortable : true,sClass : 'text-center',sWidth:"15%" },
        { mData:"open_so_qty",bSortable : true,sClass : 'text-center',sWidth:"15%" },
        { mData:"open_po_qty",bSortable : true,sClass : 'text-center',sWidth:"15%" },
        { mData:"market_price",bSortable : true,sClass : 'text-center',sWidth:"15%" },

        ],
        fnPreDrawCallback : function() { $("div.overlay").css('display','flex'); },
        fnDrawCallback : function (oSettings) {
            $("div.overlay").hide();
        },
    });
    $(document).on('click','td.qty_text', function(){
        var jeditable_route = "<?= route('manage_stock.jeditable')?>";
        console.log($(this).val());
        $(this).editable( jeditable_route, {

            tooltip: 'Click to edit...',

            type: 'text',

            data : $(this).val(),

            token : "{{ csrf_token() }}",

            indicator: 'Saving...',

            onblur:'submit',

            submitdata: function () {

                var ele = $(this).closest('tr').get(0);

                data =  master.fnGetData(ele);

                console.log(ele);

                return data;

            },

            callback: function( value, settings ) {

                var resp = $.parseJSON(value);
                console.log(resp);
                

                var redrawtable = $('#managestock_table').dataTable();

                redrawtable.fnStandingRedraw();

                $('#checkAll').prop('checked',false);
            },
            "height" : "30px"
        });
        $('.select_2').select2();
    });
    $('#company_id').change(function(){
        $('.dataTable').each(function() {
            dt = $(this).dataTable();
            dt.fnDraw();
        });
        $('#export_button').hide();
    });
    $('#supplier_id').change(function(){
        $('.dataTable').each(function() {
            dt = $(this).dataTable();
            dt.fnDraw();
        });
        $('#export_button').hide();
    });
    $('#qty').change(function(){
        $('.dataTable').each(function() {
            dt = $(this).dataTable();
            dt.fnDraw();
        });
        $('#export_button').hide();
    });
});
var checked_row = new Array();   
function DisplayerrorMsg(v){
    
    $('#chk_'+v).click(function(){
        if($('#chk_'+v).is(':checked')){
            if($('#company_id').val() == ""){
                $('#company_error').html('Select Company Name');
            }
            if($('#supplier_id').val() == ""){
                $('#spplier_error').html('Select Supplier Name');
            }
            if($('#company_id').val() != "" && $('#supplier_id').val() != ""){
                $('#company_error').html('&nbsp;');
                $('#spplier_error').html('&nbsp;');
                $('#generate_po').click(function(){
                    checked_row.push($('#chk_'+v).val());
                    checked_row = Array.from(new Set(checked_row));
                    var url = "<?= route('manage_stock.generateporesponse')?>";
                    var token = "{{csrf_token()}}";
                    $.ajax({
                        type : 'GET',
                        url : url,
                        data : {
                            'id' : checked_row,
                            '_token' : token
                        },
                        success : function(data){
                            if(data.success == 'success'){
                                var viewurl = "<?=route('manage_stock.fetchpoid',':id')?>";
                                viewurl = viewurl.replace(':id',checked_row);
                                window.location.href = viewurl;
                            }
                        }
                    });
                });
               // $.each
            }
        }
        else{
            var checkbox_length = $('input:checkbox:checked').length;
            // console.log(checkbox_length);
            if(checkbox_length == 0){
                $('#company_error').html('&nbsp;');
                $('#spplier_error').html('&nbsp;');
            }
            $('#company_id').change(function(){
                $('#company_error').html('&nbsp;');
            });
            $('#supplier_id').change(function(){
                $('#spplier_error').html('&nbsp;');
            });
        }
    });
    $('#company_id').change(function(){
        console.log('hi');
        $('#company_error').html('&nbsp;');
    });
    $('#supplier_id').change(function(){
        $('#spplier_error').html('&nbsp;');
    });
}
function DisplayAllerrorMsg(){
    if($('#checkAll').is(":checked")){
        if($('#company_id').val() == ""){
            $('#company_error').html('Select Company Name');
        }
        if($('#supplier_id').val() == ""){
            $('#spplier_error').html('Select Supplier Name');
        }
        $('#company_id').change(function(){
            $('#checkAll').prop('checked',false);
            $('#company_error').html('&nbsp;');
        });
        $('#supplier_id').change(function(){
            $('#checkAll').prop('checked',false);
            $('#spplier_error').html('&nbsp;');
        });
        if($('#company_id').val() != "" && $('#supplier_id').val() != ""){
                $('#company_error').html('&nbsp;');
                $('#spplier_error').html('&nbsp;');
                var delete_id = $('#'+table+' tbody tr .checkbox');
                $.each(delete_id,function(k,i){
                    checked_row.push(i.value);
                });
                checked_row = Array.from(new Set(checked_row));
                $('#generate_po').click(function(){
                    var url = "<?= route('manage_stock.generateporesponse')?>";
                    var token = "{{csrf_token()}}";
                    $.ajax({
                        type : 'GET',
                        url : url,
                        data : {
                            'id' : checked_row,
                            '_token' : token
                        },
                        success : function(data){
                            if(data.success == 'success'){
                                var viewurl = "<?=route('manage_stock.fetchpoid',':id')?>";
                                viewurl = viewurl.replace(':id',checked_row);
                                window.location.href = viewurl;
                            }
                        }
                    });
                });
            }
    }
    else{
        var checkbox_length = $('#checkAll:checked').length;
        
        if(checkbox_length == 0){
            $('#company_error').html('&nbsp;');
            $('#spplier_error').html('&nbsp;');
        }
    }
}
</script>
@include('admin.layout.alert')
@stop