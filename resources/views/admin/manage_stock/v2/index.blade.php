@extends('admin.layout.layout')
@section('top_fixed_content')
<nav class="navbar navbar-static-top">
    <div class="title">
        <h4><i class="fa fa-list"></i>Manage Stock  Master V2 </h4>
    </div>
    <div class="top_filter"></div>
</nav>
@stop
@section('content')
<div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="row">
                    <span class="col-md-12 combo_error text-danger" style="text-align: center"></span>
                </div>
                <div class="row">
                    <div style="width: 21%;min-width: 200px;margin-right: 10px;margin-top: 15px;margin-bottom: 10px" class="col-sm-3 col-md-3 mb-10">
                        <?=Form::select('company_id',$companies, null, array('class' => 'form-control select_2 select company', 'id' => 'company_id'))?>
                        <span class="help-inline text-danger" id="company_error">&nbsp;</span>
                    </div>
                    <div style="width: 21%;min-width: 200px;margin-right: 10px;margin-top: 15px;margin-bottom: 10px" class="col-sm-3 col-md-3">
                        <?=Form::select('supplier_id',$suppliers, null, array('class' => 'form-control select_2 select supplier', 'id' => 'supplier_id','disabled'=>'disabled'))?>
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
                            <a class="btn btn-default btn-sm" title="Generate PO" id="generate_po" onclick="postore()">Generate PO</a>
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
                            <th> Blocked Reason </th>
                            <th> Blocked By </th>
                            <th>Current Market Price</th>
                            <th>Open So Qty</th>
                            <th> Release </th>
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
            [ 10, 25, 50, -1 ],
            [ '10', '25', '50', 'Show all' ]
        ],
        "sAjaxSource": "{{  route('managestock.index') }}",
        "fnServerParams": function ( aoData ) {
            var company_id = $('#company_id').val();
            console.log("Company ID is " + company_id);
            console.log("Supplier ID is " +supplier_id);
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

                    // var o_string = JSON.stringify(o);
                    // console.log("V is " +v);
                    // console.log("T is " +t);
                    // console.log("O is " +o_string);
                    return '<div class="animated-checkbox"><label class="m-0"><input class="checkbox" type="checkbox" id="chk_'+v+'" name="special_id" value="'+v+'"/><span class="label-text" onclick="DisplayerrorMsg('+v+')"></span></label></div>';
                },
            },
            @endif
        {   
            mData:"name",
            sWidth:"16%",
            bSortable : true,
            mRender : function(v,t,o){  
                   var is_check = "<?= App\Helpers\DesignationPermissionCheck::isPermitted('product.edit') && App\Helpers\DesignationPermissionCheck::isPermitted('purchase-requisition-approval.edit')?>";
                   if(is_check != 0){
                      var edit_path = "<?= route('manage_stock.jeditable',['id'=>':id']) ?>";
                      edit_path = edit_path.replace(':id',o['id']);
                      var act_html  = '<a title="Edit '+o['company_name']+'" href="'+edit_path+'">'+ v +'</a>'
                    }
                    else{
                      var act_html  = '<a title="Edit '+o['company_name']+'">'+ v +'</a>'
                    }
                return act_html;
                },
        },
        { 
            mData:"sku",
            bSortable : true,
            sClass : 'text-center',
            sWidth:"16%",
        },
        { mData:"weight",bSortable : true,bVisible:false,sClass : 'text-center',sWidth:"15%" },
        { mData:"total_qty",bSortable : true,sClass : 'text-center',sWidth:"16%"},
        @if(App\Helpers\DesignationPermissionCheck::isPermitted('manage_stock.generateporesponse'))
        { mData:"po_qty",bSortable : true,sClass : 'text-center',sWidth:"15%" ,
            mRender: function (v, t, o) {
                return '<input class="qty_text form-control qty_only" id="qty_'+o['id']+'" type="text" name="enter_qty" value=""/><span id="qtyerror_'+o['id']+'" class="text-danger qty_error"></span><span class="text-danger disable_error" id="qtyblank_'+o['id']+'"></span>';
            },

        },
        @endif
        { mData:"total_blocked_qty",bSortable : true, bVisible:true , sClass : 'text-center',sWidth:"15%" },
        { mData:"blocked_reason",bVisible:true,sClass : 'text-center',sWidth:"16%"},
        { mData:"blocked_by",bVisible:true,sClass : 'text-center',sWidth:"16%"},
        { mData:"open_so_qty",bSortable : true, bVisible:false , sClass : 'text-center',sWidth:"15%" },
        { mData:"open_po_qty",bSortable : true, bVisible:false, sClass : 'text-center',sWidth:"15%" },
        { mData:"market_price",bSortable : true, bVisible:false, sClass : 'text-center',sWidth:"15%"
         },
         {
                    mData: 'null',
                    bSortable: false,
                    sWidth: "18px",
                    sClass: "text-center",
                    mRender: function(v, t, o) {
                        var id= o['id'];
                        var reorder_path = "<?=URL::route('manage_stock.release', ['id' => ':id'])?>";
                        reorder_path = reorder_path.replace(':id',o['id']);
                        var act_html = "<div class='btn-group'>"
                            +"<a href='"+reorder_path+"'  data-toggle='tooltip' title='Reorder' data-placement='top' class='btn btn-xs btn-info p-5' style='font-size:17px; line-height:23px; padding: 4px'><i class='fa fa-fw fa-copy'></i></a>"
                            +"</div>";
                        return act_html;
                    },
                },

        ],
        fnPreDrawCallback : function() { $("div.overlay").css('display','flex'); },
        fnDrawCallback : function (oSettings) {
            $("div.overlay").hide();
            $('.qty_text').hide();
            $(".qty_only").keypress(function(h){if(h.which!=8&&h.which!=0&&(h.which<48||h.which>57))return!1});
        },
    });
    postore();
    $('#company_id').change(function(){
        var company_id = $(this).val();
        var company_url = "{{ url('admin/managestock/getsupplier') }}"
        $.ajax({
            type : 'POST',
            url : company_url,
            data : {
                'company_id' : company_id,
                '_token' : "{{csrf_token()}}"
            },
            success : function(data){
                $('#supplier_id').attr('disabled',false);
                $("#supplier_id").html('');
                var supplier_options = $(data).html();
                $("#supplier_id").html(supplier_options);
                if(supplier_options != null){
                    $("#supplier_id").val(supplier_options).trigger('change');
                    $('#supplier_id').select2({'placeholder':'Select Supplier'});
                }
            },
            error : function(data){

                    console.log(data);

            }
        });
        $('.dataTable').each(function() {
            dt = $(this).dataTable();
            dt.fnDraw();
        });
        $('#export_button').hide();
        $('.combo_error').text('');
    });
    $('#supplier_id').change(function(){
        $('.dataTable').each(function() {
            dt = $(this).dataTable();
            dt.fnDraw();
        });
        $('#export_button').hide();
        $('.combo_error').text('');
    });
    $('#qty').change(function(){
        $('.dataTable').each(function() {
            dt = $(this).dataTable();
            dt.fnDraw();
        });
        $('#export_button').hide();
        $('.combo_error').text('');
    });
});
var checked_row = new Array();
var qty_array = new Array();   
function DisplayerrorMsg(v){
    $('#chk_'+v).click(function(){
        $('#qty_'+v).val('');
        $('#qty_'+v).show();
        var enter_qty_value = 0;
        if( $('#qty_'+v).val() == ''){
            $('#qtyblank_'+v).text('Qty Could not be blank.');
        }
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
                $('.combo_error').text('');
                $('#qty_'+v).keyup(function(){
                    var qty_v = $(this).val();
                    $('#qtyerror_'+v).text('');
                    $('#qtyblank_'+v).text('');
                    if(qty_v != '0'){
                        var url = "<?= route('manage_stock.generateporesponse')?>";
                        var token = "{{csrf_token()}}";
                        var company_id = $('#company_id').val();
                        var supplier_id = $('#supplier_id').val();
                        $.ajax({
                            type : 'GET',
                            url : url,
                            data : {
                                'id' : v,
                                'qty_value' : qty_v,
                                'company_id' : company_id,
                                'supplier_id' : supplier_id,
                                '_token' : token
                            },
                            success : function(data){
                            }
                        });
                    }
                    else{
                        $('#qtyerror_'+v).text('Qty Could not be 0.');
                    }
                });
            }
        }
        else{
            var url = "<?= route('manage_stock.removepoitem')?>";
            var token = "{{csrf_token()}}";
            var id = v;
            var new_qty_array = qty_array;
            var removeItem = $('#qty_'+v).val();
            new_qty_array = jQuery.grep(new_qty_array, function(value) {
              return value != removeItem;
            });
            $.ajax({
                type : 'GET',
                url : url,
                data : {
                    'id' : id,
                    'qty_value' : new_qty_array,
                    '_token' : token
                },
                success : function(data){
                }
            });
            $('#qty_'+v).hide();
            var checkbox_length = $('input:checkbox:checked').length;

            $('#qtyblank_'+v).text('');
            // console.log(checkbox_length);
            if(checkbox_length == 0){
                $('#company_error').html('&nbsp;');
                $('#spplier_error').html('&nbsp;');
                $('.combo_error').text('');
            }
            $('#company_id').change(function(){
                $('#company_error').html('&nbsp;');
                $('.combo_error').text('');
            });
            $('#supplier_id').change(function(){
                $('#spplier_error').html('&nbsp;');
                $('.combo_error').text('');
            });
        }
    });
    $('#company_id').change(function(){
        $('#company_error').html('&nbsp;');
        $('.combo_error').text('');
    });
    $('#supplier_id').change(function(){
        $('#spplier_error').html('&nbsp;');
        $('.combo_error').text('');
    });
    postore();
}
function DisplayAllerrorMsg(){
    if($('input[name="special_id"]').is(':checked') == false){
        $('.qty_text').show();
        if( $('.qty_text').val() == ''){
            $('.disable_error').text('Qty Could not be blank.');
        }
        if($('#company_id').val() == ""){
            $('#company_error').html('Select Company Name');
        }
        if($('#supplier_id').val() == ""){
            $('#spplier_error').html('Select Supplier Name');
        }
        $('#company_id').change(function(){
            $('#checkAll').prop('checked',false);
            $('#company_error').html('&nbsp;');
            $('.combo_error').text('');
        });
        $('#supplier_id').change(function(){
            $('#checkAll').prop('checked',false);
            $('#spplier_error').html('&nbsp;');
            $('.combo_error').text('');
        });
        if($('#company_id').val() != "" && $('#supplier_id').val() != ""){
            $('#company_error').html('&nbsp;');
            $('#spplier_error').html('&nbsp;');
            $('.combo_error').text('');
            $('.qty_text').keyup(function(){
                $('#qtyerror_'+id).text('');
                var qty_v = $(this).val();
                var id = $(this).parent().parent().find('.checkbox').val();
                if(qty_v != '0'){
                    var url = "<?= route('manage_stock.generateporesponse')?>";
                    var company_id = $('#company_id').val();
                    var supplier_id = $('#supplier_id').val();
                    var token = "{{csrf_token()}}";
                    $.ajax({
                        type : 'GET',
                        url : url,
                        data : {
                            'id' : id,
                            'qty_value' : qty_v,
                            'company_id' : company_id,
                            'supplier_id' : supplier_id,
                            '_token' : token
                        },
                        success : function(data){
                        }
                    });
                }
                else{
                    $('#qtyerror_'+id).text('Qty Could not be 0.');
                }
            });
        }
    }
    if($('input[name="special_id"]').is(':checked') == true){
        console.log(2);
        $('.combo_error').text('');
        $('.qty_text').hide();
        $('.disable_error').text('');
        var checkbox_length = $('#checkAll:checked').length;
        
        if(checkbox_length == 0){
            $('#company_error').html('&nbsp;');
            $('#spplier_error').html('&nbsp;');
        }
    }
}
var checked_row_array = new Array();
var qty_array = new Array();
function postore(){
        if($('input[name="special_id"]:checked').prop('checked')){
            $('input[name="special_id"]:checked').each(function() {
               var v = this.value;
               var enter_qty_value = 0;
                if( $('#qty_'+v).val() == ''){
                    $('#qtyblank_'+v).text('Qty Could not be blank.');
                }
                $('#qty_'+v).keyup(function(){
                    $('#qtyerror_'+v).text('');
                    $('#qtyblank_'+v).text('');
                    enter_qty_value = $(this).val();
                    qty_array.push(enter_qty_value);
                    qty_array = Array.from(new Set(qty_array));
                });
                if($('#company_id').val() == ""){
                    $('#company_error').html('Select Company Name');
                }
                if($('#supplier_id').val() == ""){
                    $('#spplier_error').html('Select Supplier Name');
                }
                if($('#company_id').val() != "" && $('#supplier_id').val() != ""){
                    $('#company_error').html('&nbsp;');
                    $('#spplier_error').html('&nbsp;');
                    $('.combo_error').text('');
                    $('#qty_'+v).keyup(function(){
                        checked_row.push($('#chk_'+v).val());
                        checked_row = Array.from(new Set(checked_row));
                        enter_qty_value = $(this).val();
                        qty_array.push(enter_qty_value);
                        qty_array = Array.from(new Set(qty_array));
                        var qty_v = $(this).val();
                        if(qty_v != '0'){
                            var url = "<?= route('manage_stock.generateporesponse')?>";
                            var company_id = $('#company_id').val();
                            var supplier_id = $('#supplier_id').val();
                            var token = "{{csrf_token()}}";
                            $.ajax({
                                type : 'GET',
                                url : url,
                                data : {
                                    'id' : v,
                                    'qty_value' : qty_v,
                                    'company_id' : company_id,
                                    'supplier_id' : supplier_id,
                                    '_token' : token
                                },
                                success : function(data){
                                }
                            });
                         }
                        else{
                            $('#qtyerror_'+v).text('Qty Could not be 0.');
                        }
                });
                $('#generate_po').click(function(){
                    if($('.combo_error').text() != 'Select Product.' && $('.combo_error').text() != 'Select Company Name ,Supplier Name and Product.' ){
                        var qty_error_val = $('.qty_error').text();
                        // console.log(qty_error_val);
                        var qty_blank_val = $('.disable_error').text();
                        if(qty_blank_val.length == 0){
                            if(qty_error_val.length == 0){
                                checked_row.push($('#chk_'+v).val());
                                checked_row = Array.from(new Set(checked_row));
                                var viewurl = "<?=route('manage_stock.randomredirect')?>";
                                var token = "{{csrf_token()}}";
                                $.ajax({
                                    type : 'GET',
                                    url : viewurl,
                                    data : {
                                        'id' : checked_row,
                                        'qty_value' : qty_array,
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
                            }
                        }
                    }
                });
                }
            });
        }
        else{
            $('#generate_po').click(function(){
                console.log(2);
                if($('#company_id').val() == ""){
                    $('.combo_error').text('Select Company Name ,Supplier Name and Product.');
                }
                if($('#supplier_id').val() == ""){
                    $('.combo_error').text('Select Company Name ,Supplier Name and Product.');
                }
                if($('#company_id').val() != "" && $('#supplier_id').val() != ""){
                    if($('input[name="special_id"]').is(':checked') == false){
                        $('.combo_error').text('Select Product.');
                    }
                }
            });
        }
}
</script>
@include('admin.layout.alert')
@stop