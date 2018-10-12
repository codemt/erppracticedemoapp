@extends('admin.layout.layout')
@section('start_form')
     <?=Form::model($sales_order,['method' => 'patch', 'route' => ['salesorder.update',$id], 'class' => 'm-0 form-horizontal','files'=>true])?>
   
    <input type="hidden" name="id" value="<?= $id?>" id="id">
@stop
@section('top_fixed_content')
<div id="loader" style="display: none;">
  <div class="loader"><img src="<?= IMAGE_PATH.'backend/images/loader.gif'?>"></div>
</div>
<nav class="navbar navbar-static-top">
    <div class="title">
         <h4></h4>
    </div>
    <div class="pl-10">
        <!-- <button type="button" name="save_button" ng-click="completeSalesOrder()" value="save_new" class="btn btn-primary btn-sm disabled-btn" title="Save and add new">Save & New </button>
        <a href="javascript:void(0);" ng-click="completeSalesOrder()" >Save & New </a>
        <button type="submit" name="save_button" value="save_exit" class="btn btn-primary btn-sm disabled-btn" title="Save and exit">Save & exit</button>
        <a href="<?=URL::route('salesorder.index')?>" class="btn btn-default btn-sm" title="Back to users Page">Cancel</a> -->
    </div>
</nav>
@stop
@section('content')
<div ng-app="salesorderApp" ng-controller="SalesOrderController as soc" ng-clock >
    <div class="row">
        <div class="col-md-12 mb-30">
            <div class="card">
                <div class="card-title-w-btn">
                        <h4 class="title">Sales Order</h4>
                </div><hr>
                <div class="row">
                    <div class="col-md-12">
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group so_no_error_div">
                                    <div class="col-md-12">SO No.<sup class="text-danger">*</sup></div>
                                    <div class="col-md-12">
                                        <?= Form::text('so_no',old('so_no'),array('class' => 'form-control','placeholder'=>'SO No.','readonly'=>true,'ng-model'=>'salesorder.so_no','id'=>'so_no')) ?>
                                        <span id="so_no_error" class="help-inline text-danger"><?=$errors->first('so_no')?></span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group" id="po_no_error_div">
                                    <div class="col-md-12">PO No.<sup class="text-danger">*</sup></div>
                                    <div class="col-md-12">
                                        <?= Form::text('po_no', old('po_no'),array('class' => 'form-control','placeholder'=>'PO No.','ng-model'=>'salesorder.po_no')) ?>
                                        <span id="po_no_error" class="help-inline text-danger"><?=$errors->first('po_no')?></span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group " id="order_date_error_div">
                                    <div class="col-md-12">PO Date<sup class="text-danger">*</sup></div>
                                    <div class="col-md-12">
                                        <?= Form::text('order_date', old('order_date'),array('class' => 'form-control','id'=>'order_date','placeholder'=>'dd-mm-yyyy','ng-model'=>'salesorder.order_date')) ?>
                                    <span id="order_date_error" class="help-inline text-danger"><?=$errors->first('order_date')?></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-12">  
                        <div class="row">  
                            <div class="col-md-4">
                                <div class="form-group" id="company_id_error_div">
                                    <div class="col-md-12">Select Company<sup class="text-danger">*</sup></div>
                                    <div class="col-md-12">
                                        <?= Form::select('company_id',$companies, old('company_id'),array('class' => 'form-control select2','id'=>'company_id','ng-model'=>'salesorder.company_id','ng-change'=>'soc.getProduct()')) ?>
                                        <span id="company_id_error" class="help-inline text-danger"><?=$errors->first('company_id')?></span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group" id="customer_id_error_div">
                                    <div class="col-md-12">Select Customer<sup class="text-danger">*</sup></div>
                                    <div class="col-md-12">
                                        <?= Form::select('customer_id',$customers, old('customer_id'),array('class' => 'form-control select2','id'=>'customer_id','ng-model'=>'salesorder.customer_id','ng-change'=>'changeSalesPerson()')) ?>
                                        <span id="customer_id_error" class="help-inline text-danger"><?=$errors->first('customer_id')?></span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group " id="billing_title_error_div">
                                    <div class="col-md-12">Billing Address<sup class="text-danger">*</sup></div>
                                    <div class="col-md-12">
                                        <?= Form::select('billing_title',$billing_address ,old('billing_title',$sales_order->billing_id),array('class' => 'form-control select2','id'=>'billing_title','ng-model'=>'salesorder.billing_title')) ?>
                                        <span id="billing_title_error" class="help-inline text-danger"><?=$errors->first('billing_title')?></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group " id="customer_contact_name_error_div">
                                    <div class="col-md-12">Customer Contact Name<sup class="text-danger">*</sup></div>
                                    <div class="col-md-12">
                                        <?= Form::text('customer_contact_name', old('customer_contact_name'),array('class' => 'form-control','placeholder'=>'Contact Name','id'=>'customer_contact_name','ng-model'=>'salesorder.customer_contact_name')) ?>
                                        <span id="customer_contact_name_error" class="help-inline text-danger"><?=$errors->first('customer_contact_name')?></span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group " id="customer_contact_email_error_div">
                                    <div class="col-md-12">Customer Contact Email<sup class="text-danger">*</sup></div>
                                    <div class="col-md-12">
                                        <?= Form::text('customer_contact_email', old('customer_contact_email'),array('class' => 'form-control','placeholder'=>'Contact Email','id'=>'customer_contact_email','ng-model'=>'salesorder.customer_contact_email')) ?>
                                        <span id="customer_contact_email_error" class="help-inline text-danger"><?=$errors->first('customer_contact_email')?></span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group " id="customer_contact_no_error_div">
                                    <div class="col-md-12">Customer Contact Number<sup class="text-danger">*</sup></div>
                                    <div class="col-md-12">
                                        <?= Form::text('customer_contact_no', old('customer_contact_no'),array('class' => 'form-control number_only','placeholder'=>'Contact Number','id'=>'customer_contact_no','ng-model'=>'salesorder.customer_contact_no')) ?>
                                        <span id="customer_contact_no_error" class="help-inline text-danger"><?=$errors->first('customer_contact_no')?></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div> 
                    <div class="col-md-12 ">
                        <div class="row">   
                            <div class="col-md-12 mb-10">    
                                <div class="animated-checkbox">
                                    <label class="control-label">
                                        <input type="checkbox" class="form-control" name="check_billing" id="check_billing" ng-checked="change_status(salesorder.check_billing)" ng-model="salesorder.check_billing">
                                        <span class="label-text"> Same as Billing Address</span>
                                    </label>
                                </div>    
                            </div>    
                        </div>
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group " id="shipping_address_error_div">
                                    <div class="col-md-12">Shipping Address<sup class="text-danger">*</sup></div>
                                    <div class="col-md-12">
                                        <?= Form::text('shipping_address', old('shipping_address'),array('class' => 'form-control shipping_address','id'=>'shipping_address','ng-model'=>'salesorder.shipping_address','placeholder'=>'Shipping Address')) ?>
                                        <div id="suggesstion-box"></div>
                                        <span id="shipping_address_error" class="help-inline text-danger"><?=$errors->first('shipping_address')?></span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group " id="countryid_error_div">
                                    <div class="col-md-12">Select Country<sup class="text-danger">*</sup></div>
                                    <div class="col-md-12">
                                        <?= Form::select('countryid',$countries, old('countryid'),array('class' => 'form-control select2','id'=>'countryid','ng-model'=>'salesorder.countryid')) ?>
                                        <span id="countryid_error" class="help-inline text-danger"><?=$errors->first('countryid')?></span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group " id="stateid_error_div">
                                    <div class="col-md-12">Select State<sup class="text-danger">*</sup></div>
                                    <div class="col-md-12">
                                        <?= Form::select('stateid',$states, old('stateid'),array('class' => 'form-control select2','id'=>'stateid','ng-model'=>'salesorder.stateid')) ?>
                                        <img id="state_loader" class="input-loading" src="{{ asset('backend/images/loading.gif') }}" alt="Loading..."/>
                                        <span id="stateid_error" class="help-inline text-danger"><?=$errors->first('stateid')?></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group " id="cityid_error_div">
                                    <div class="col-md-12">Select City<sup class="text-danger">*</sup></div>
                                    <div class="col-md-12">
                                        <?= Form::select('cityid',$cities, old('cityid'),array('class' => 'form-control select2','id'=>'cityid','ng-model'=>'salesorder.cityid')) ?>
                                        <img id="city_loader" class="input-loading" src="{{ asset('backend/images/loading.gif') }}" alt="Loading..."/>
                                        <span id="cityid_error" class="help-inline text-danger"><?=$errors->first('cityid')?></span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group " id="pin_code_error_div">
                                    <div class="col-md-12">Pincode<sup class="text-danger">*</sup></div>
                                    <div class="col-md-12">
                                        <?= Form::text('pin_code', old('pin_code'),array('class' => 'form-control number_only','placeholder'=>'Pincode','id'=>'pin_code','ng-model'=>'salesorder.pin_code')) ?>
                                        <span id="pin_code_error" class="help-inline text-danger"><?=$errors->first('pin_code')?></span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group " id="contact_name_error_div">
                                    <div class="col-md-12">Contact Name<sup class="text-danger">*</sup></div>
                                    <div class="col-md-12">
                                        <?= Form::text('contact_name', old('contact_name'),array('class' => 'form-control','placeholder'=>'Contact Name','id'=>'contact_name','ng-model'=>'salesorder.contact_name')) ?>
                                        <span id="contact_name_error" class="help-inline text-danger"><?=$errors->first('contact_name')?></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group " id="contact_email_error_div">
                                    <div class="col-md-12">Contact Email<sup class="text-danger">*</sup></div>
                                    <div class="col-md-12">
                                        <?= Form::text('contact_email', old('contact_email'),array('class' => 'form-control','placeholder'=>'Contact Email','id'=>'contact_email','ng-model'=>'salesorder.contact_email')) ?>
                                        <span id="contact_email_error" class="help-inline text-danger"><?=$errors->first('contact_email')?></span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group " id="contact_no_error_div">
                                    <div class="col-md-12">Contact Number<sup class="text-danger">*</sup></div>
                                    <div class="col-md-12">
                                        <?= Form::text('contact_no', old('contact_no'),array('class' => 'form-control number_only','placeholder'=>'Contact Number','id'=>'contact_no','ng-model'=>'salesorder.contact_no')) ?>
                                        <span id="contact_no_error" class="help-inline text-danger"><?=$errors->first('contact_no')?></span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group " id="project_name_error_div">
                                    <div class="col-md-12">Project Name</div>
                                    <div class="col-md-12">
                                        <?= Form::text('project_name', old('project_name'),array('class' => 'form-control','placeholder'=>'Project Name','id'=>'project_name','ng-model'=>'salesorder.project_name')) ?>
                                        <span id="contact_name_error" class="help-inline text-danger"><?=$errors->first('project_name')?></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div> 
                    <div class="col-md-12">
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group " id="location_error_div">
                                    <div class="col-md-12">Location</div>
                                    <div class="col-md-12">
                                        <?= Form::text('location', old('location'),array('class' => 'form-control','placeholder'=>'Location','id'=>'location','ng-model'=>'salesorder.location')) ?>
                                        <span id="contact_email_error" class="help-inline text-danger"><?=$errors->first('location')?></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>  
    <div class="row">
        <div class="col-md-12 mb-30">
            <div class="card">        
                <div class="row">
                    <div class="col-md-12">
                        <span class="text-danger" ng-if="add_select == 1">Please FillUp All Fields</span>
                    </div>
                    <div class=" col-md-2">
                        <div class="row form-group">
                            <div class="col-md-12">Supplier<sup class="text-danger">*</sup></div>
                            <div class="col-md-12">
                                <select name="supplier" ng-model="selectedSupplier" class='form-control select2' id="supplier" ng-disabled="!(!!salesorder.company_id)" ng-change='soc.getProduct()'>
                                    <option value="">Select supplier</option>
                                    <option ng-repeat="supplier in soc.suppliers" value="{% supplier.id %}">{% supplier.supplier_name %}</option>
                                </select>
                                <span class="text-danger" id="supplier_error"><b>{{ $errors->first('supplier') }}</b></span>
                            </div>
                        </div>
                    </div>
                    <div class=" col-md-2">
                        <div class="row form-group">
                            <div class="col-md-12">Product<sup class="text-danger">*</sup></div>
                            <div class="col-md-12">
                                <select name="product" ng-model="selectedProduct" class='form-control select2' id="products" ng-disabled="!(!!selectedSupplier)">
                                    <option ng-repeat="product in soc.products" value="{% product.id %}">{% product.model_no %} {% product.units_measure %}</option>
                                </select>
                                <span class="text-danger" id="product_error"><b>{{ $errors->first('product') }}</b></span>
                            </div>
                        </div>
                    </div>
                    <div class=" col-md-2">
                        <div class="row form-group">
                            <div class="col-md-12">Quantity<sup class="text-danger">*</sup></div>
                            <div class="col-md-12">
                                <?= Form::number('quantity',0,['id'=>'quantity','class'=>'form-control number_only','min'=>0,'ng-model' => 'selectedQuantity','id'=>'quantity','ng-disabled'=>"!(!!selectedSupplier)"]) ?>
                                <span class="text-danger" id="quantity_error">{{ $errors->first('quantity') }}</span>
                            </div>
                        </div>
                    </div>
                    <div class=" col-md-2">
                        <div class="row form-group">
                            <div class="col-md-12">Unit Value<sup class="text-danger">*</sup></div>
                            <div class="col-md-12">
                                <?= Form::text('unit_value',old('unit_value'),['id'=>'unit_value','class'=>'form-control number_only','ng-model' => 'selectedunitvalue','id'=>'unit_value','ng-disabled'=>"!(!!selectedSupplier)"]) ?>
                                <span class="text-danger" id="unit_value_error">{{ $errors->first('unit_value') }}</span>
                            </div>
                        </div>
                    </div>
                    <div class=" col-md-2">
                        <div class="row form-group">
                            <div class="col-md-12">Manu. Clearance<sup class="text-danger">*</sup></div>
                            <div class="col-md-12">
                                <?= Form::select('manu_clearance',config('Constant.manu_clearance'),old('manu_clearance'),['id'=>'manu_clearance','class'=>'form-control select2','ng-model' => 'selectedmanuclearance','ng-disabled'=>"!(!!selectedSupplier)"]) ?>
                                <span class="text-danger" id="manu_clearance_error">{{ $errors->first('manu_clearance') }}</span> 
                            </div>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <a href="javascript:void(0);" ng-click="soc.addItem();" class="btn btn-primary " style="margin-top: 19px" id="add_item" ng-disabled="!(!!selectedSupplier)">Add Item</a>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-2">
                        <div class="row form-group ">
                            <div class="col-md-12">Supplier<sup class="text-danger">*</sup></div>
                            <div class="col-md-12">
                                <select name="supplierForMulti" ng-model="selectedSupplierForMulti" class='form-control select2' id="supplierForMulti" ng-disabled="!(!!salesorder.company_id)" ng-change='soc.getProduct()'>
                                    <option>Select Supplier</option>
                                
                                    <option ng-repeat="supplier in soc.suppliers" value="{% supplier.id %}">{% supplier.supplier_name %}</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class=" col-sm-7">
                        <a href="javascript:void(0);" style="margin-top: 19px" ng-click="soc.openModal()" class="btn btn-primary btn-sm" id="order_multiple" ng-disabled="(!(!!salesorder.company_id) || !(!!selectedSupplierForMulti))">Order Multiple</a>
                    </div>
                   <!--  <div class="col-sm-offset-2 col-sm-10">
                        <a href="javascript:void(0);" ng-click="soc.openModal()" class="btn btn-primary btn-sm" id="order_multiple" ng-disabled="!(!!salesorder.company_id)">Order Multiple</a>
                    </div> -->
                    <div id="no_order_div" class="row" style="display:none;">
                        <div class="col-md-12">
                            <h3 class="text-center no_order text-info">No items added</h3>
                        </div>
                    </div>
                    <div class="clearfix"></div>
                    <div id="orderTableDiv" >
                        <div class="col-md-12">
                            <h3 class="text-info text-center">Order Items</h3>
                        </div>
                        <div class="col-md-12 table-responsive">
                            <table class="table table-striped table-bordered table-hover dt-responsive nowrap" >
                                <thead>
                                    <th>Description</th>
                                    <th>Model Number</th>
                                    <th>Supplier</th>
                                    <th>QTY</th>
                                    <th>Unit Value</th>
                                    <th>Total Value</th>
                                    <th>List Price</th>
                                    <th>Manu. Clearance</th>
                                    <th>Tax</th>
                                    <th>Discount Applied</th>
                                    <th></th>
                                </thead>
                                <tbody>
                                    <tr ng-repeat="(k,cartProduct) in soc.cartProducts">
                                        <td ng-cloak>{% cartProduct.productname %}</td>
                                        <td>{% cartProduct.model_no %}</td>
                                        <td>{% cartProduct.suppliers %}</td>
                                        <td>
                                            <span ng-hide="cartProduct.editing" ng-click="soc.editQty(cartProduct)">{% cartProduct.quantity %}</span>
                                            <input type="number" ng-show="cartProduct.editing" ng-model="cartProduct.quantity" ng-blur="soc.doneChangeQty(cartProduct)" class="number_only" autofocus />
                                        </td>
                                        <td><span ng-hide="cartProduct.unitediting" ng-click="soc.editUnitValue(cartProduct)">{% cartProduct.unit_value %}</span><input type="text" ng-show="cartProduct.unitediting" ng-model="cartProduct.unit_value" ng-blur="soc.doneChangeUnitValue(cartProduct)" autofocus style="width: 80px" class="number_only" /></td>
                                        <td>{% (cartProduct.quantity * cartProduct.unit_value)%}</td>
                                        <td>{% cartProduct.price |number:2 %}</td>
                                        <td><span ng-hide="cartProduct.manuclearanceediting" ng-click="soc.editManuClearance(cartProduct)">{% cartProduct.manu_clearance %}</span><?= Form::select('manu',config('Constant.manu_clearance'),null,['class'=>'form-control select2','ng-model' => 'cartProduct.manu_clearance','ng-show'=>"cartProduct.manuclearanceediting",'ng-blur'=>'soc.doneChangeManuClearance(cartProduct)','style'=>'width:50px;']) ?></td>

                                        <td>{% (((cartProduct.quantity * cartProduct.unit_value)*cartProduct.tax)/100) | number:2 %}</td>
                                        <td ng-if="(cartProduct.max_discount < (100 - (cartProduct.unit_value*100/cartProduct.price)))" style="color:red;">{% (100 - (cartProduct.unit_value*100/cartProduct.price)) | number:2 %}%</td>
                                        <td ng-if="(cartProduct.max_discount > (100 - (cartProduct.unit_value*100/cartProduct.price)))">{% (100 - (cartProduct.unit_value*100/cartProduct.price)) | number:2 %}%</td>
                                        <td><button ng-click='soc.removeItem(cartProduct)' class='btn btn-danger btn-sm'>X</button></td>
                                    </tr>
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <td colspan="4">
                                        </td>
                                        <td><b>Total Amount</b></td>
                                        <td colspan="2">{% orderTotal | number:2 %}</td>
                                        <input type="hidden" name="total_amount" ng-nodel="salesorder.total_amount">
                                        <td><b>Total Tax Amount</b></td>
                                        <td colspan="2">{% orderTaxTotal | number:2 %}</td>
                                        <input type="hidden" name="total_tax_amount" ng-nodel="salesorder.total_tax_amount">
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                </div>    
            </div>
        </div>
        <script type="text/ng-template" id="salesorderHtml.html">
                <div class="modal-header">
                    <button type="button" class="close" ng-click="soc.modalCancel()">&times;</button>
                    <h3 class="modal-title" id="modal-title">{%new_product[0].supplier_name%}'s Products</h3>
                </div>
                <div class="modal-body table-container" id="modal-body">
                    <div class=" col-md-12">
                        <div class="row form-group">
                            <div class="col-md-4 pull-right p-0">
                                <input type="text" name="" ng-model="product_search" class="form-control" placeholder="Search">
                            </div>
                        </div>
                    </div>
                    <table class="table scrollable table-striped table-bordered table-condensed table-hover dt-responsive nowrap" id="multiple_order" style="max-height: 500px !important;overflow-y: auto;display: inline-block;width: 100%;">
                        <thead>
                            <th class="col-md-3">Model No.</th>
                            <th class="col-md-3">Supplier</th>
                            <th class="col-md-1">QTY</th>
                            <th class="col-md-3">Unit Value</th>
                            <th class="col-md-1">List Price</th>
                            <th class="col-md-2">Manu. Clearance</th>
                            <th class="col-md-1">Discount Applied</th>
                            <th class="col-md-2">Total</th>
                        </thead>
                        <tbody>
                            <tr ng-repeat="(k,product) in new_product | filter : product_search">
                                <td class="col-md-3">{% product.model_no %}</td>
                                <td class="col-md-3">{% product.supplier_name %}</td>
                                <td class="col-md-1">
                                    <input type="number" min="0" name="new_qty" class="input-sm form-control number_only" ng-model="product.new_qty" />
                                </td>
                                <td class="col-md-3">
                                    <?= Form::text('unit_value',old('unit_value'),['id'=>'unit_value','class'=>'form-control input-sm number_only','ng-model' => 'product.unit_value']) ?>
                                </td>
                                <td class="col-md-1">{% product.price |number:2 %}</td>
                                <td class="col-md-2">
                                    <?= Form::select('manu_clearance',config('Constant.manu_clearance'),old('manu_clearance'),['id'=>'manu_clearance','class'=>'form-control input-sm select2','ng-model' => 'product.manu_clearance']) ?>
                                </td>
                                <td class="col-md-1">
                                    <span ng-if="((product.unit_value != 0) && (product.max_discount < (100 - (product.unit_value*100/product.price))))" style="color:red;">{% (100 - (product.unit_value*100/product.price)) | number:2 %}% </span>

                                    <span ng-if="((product.unit_value != 0) && (product.max_discount > (100 - (product.unit_value*100/product.price))))">{% (100 - (product.unit_value*100/product.price)) | number:2 %}% </span>

                                </td>
                                <td class="col-md-2"><span ng-if="(product.new_qty != NULL && product.unit_value != NULL)">{% (product.new_qty * product.unit_value)| number:2 %} </span></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-primary" type="button" ng-click="soc.modalOk()">OK</button>
                    <button class="btn btn-warning" type="button" ng-click="soc.modalCancel()">Cancel</button>
                </div>
        </script> 
        <script type="text/ng-template" id="salesorderview.html">
            <div class="modal-header">
                <button type="button" class="close" ng-click="soc.modalCancel()">&times;</button>
                <h3 class="modal-title" id="modal-title">Sales Order</h3>
            </div>
            <div class="modal-body table-container" id="modal-body">
                <div style="display: inline-block;width: 100%;margin-bottom: 30px;">
                    <table style="width: 100%;border: medium none;" class="so_view">
                        <caption style="font-size: 20px;margin-bottom: 20px;font-weight: bold;text-align: center;">Sales Order Acknowledgment</caption>
                        <tr>
                            <td style="border: medium none;width: 48%;padding: 0">
                                <table style="border: medium none;width: 100%;">
                                    <tr>
                                        <td style="font-size: 13px;border: medium none;width: 40%;padding-left:0 "><strong>SOA No:</strong></td>
                                        <td style="width: 60%">{% sales_order_data.so_no%}</td>
                                    </tr>
                                    <tr>
                                        <td style="font-size: 13px;border: medium none;width: 40%;padding-left:0 "><strong>Date:</strong></td>
                                        <td style="width: 60%">{% sales_order_data.created_at%}</td>
                                    </tr>
                                </table>
                            </td>
                            <td style="width: 4%;border: medium none;"></td>
                            <td style="border: medium none;width: 48%;padding: 0">
                                <table style="border: medium none;width: 100%;">
                                    <tr>
                                        <td style="font-size: 13px;border: medium none;width: 40%;text-align: left;float: left"><strong>PO No:</strong></td>
                                        <td style="width: 60%;">{% sales_order_data.po_no%}</td>
                                    </tr>
                                    <tr>
                                        <td style="font-size: 13px;border: medium none;width: 40%;text-align: left;float: left"><strong>Date:</strong></td>
                                        <td style="width: 60%;">{% sales_order_data.order_date | date:'dd, MMMM yyyy' %}</td>
                                    </tr>
                                </table>
                            </td>
                        </tr>
                    </table>
                </div>
                <div style="display: inline-block;width: 100%;margin-bottom: 15px">
                    <table style="width: 100%;border: medium none;" class="so_view">
                        <tr>
                            <td style="width: 48%;padding: 0">
                                <table style="width: 100%;border: medium none;">
                                    <tr>
                                        <td colspan="2" style="border: medium none;background-color: #00AEEF;color: #ffffff;"><strong style="font-size:16px">Bill To</strong></td>
                                    </tr>
                                    <tr>
                                        <td colspan="2" style="border: medium none;">{%sales_order_data.company_name%}</td>
                                    </tr>
                                    <tr>
                                        <td colspan="2" style="border: medium none;">{%sales_order_data.billing_address%}</td>
                                    </tr>
                                    <tr>
                                        <td style="border: medium none;">City: {%sales_order_data.bill_city%}</td>
                                        <td style="border: medium none;">Pincode: {{$sales_order_data['bill_pincode']}}</td>
                                    </tr>
                                    <tr>
                                        <td style="border: medium none;">State: {%sales_order_data.bill_state%}</td>
                                        <td style="border: medium none;">Country: {%sales_order_data.bill_country%}</td>
                                    </tr>
                                    <tr>
                                        <td width="100%" style="border: medium none;" colspan="2" valign="top"><span style="width: 30%;display: inline-block;vertical-align: top">Contact Details :</span><span style="width: 70%;display: inline-block;vertical-align: top">{%sales_order_data.contact_name%}/{%sales_order_data.contact_email%}/<br>{%sales_order_data.contact_no%}</span></td>
                                    </tr>
                                </table>
                            </td>
                            <td style="width: 2%;border: medium none;"></td>
                            <td style="width: 48%;padding: 0">
                                <table style="width: 100%;border: medium none;">
                                    <tr>
                                        <td colspan="2" style="border: medium none;background-color: #00AEEF;color: #ffffff;"><strong style="font-size:16px">Ship To</strong></td>
                                    </tr>
                                    <tr>
                                        <td colspan="2" style="border: medium none;">{%sales_order_data.company_name%}</td>
                                    </tr>
                                    <tr>
                                        <td colspan="2" style="border: medium none;">{%sales_order_data.shipping_address%}</td>
                                    </tr>
                                    <tr>
                                        <td style="border: medium none;">City: {%sales_order_data.ship_city%}</td>
                                        <td style="border: medium none;">Pincode: {%sales_order_data.pin_code%}</td>
                                    </tr>
                                    <tr>
                                        <td style="border: medium none;">State: {%sales_order_data.ship_state%}</td>
                                        <td style="border: medium none;">Country: {%sales_order_data.ship_country%}</td>
                                    </tr>
                                    <tr>
                                        <td width="100%" style="border: medium none;" colspan="2" valign="top"><span style="width: 30%;display: inline-block;vertical-align: top">Contact Details :</span><span style="width: 70%;display: inline-block;vertical-align: top"> {%sales_order_data.contact_name%}/{%sales_order_data.contact_email%}/<br>{%sales_order_data.contact_no%}</span></td>
                                    </tr>
                                </table>
                            </td>
                        </tr>
                    </table>
                </div>
                <br style="clear: both">
                <div style="display: inline-block;width: 100%;margin-bottom: 15px">
                    <table style="width: 100%;" class="so_view">
                        <tr>
                            <th style="background-color: gray">OA Reference</th>
                            <th style="background-color: gray">Payment Terms</th>
                            <th style="background-color: gray">Delivery Terms</th>
                            <th style="background-color: gray">Ship Via</th>
                            <th style="background-color: gray">Destination</th>
                        </tr>
                        <tr>
                            <td>{%sales_order_data.so_no%}</td>
                            <td>{%sales_order_data.payment_terms%}</td>
                            <td>{%sales_order_data.delivery%}</td>
                            <td>{%sales_order_data.trasport%}</td>
                            <td>{%sales_order_data.ship_city%}</td>
                        </tr>
                    </table>
                </div>
                <br style="clear: both">
                <div style="display: inline-block;width: 100%;margin-bottom: 15px">
                    <table style="width: 100%;border: medium none;" class="border-bottom so_view">
                        <tr style="border: medium none;background-color: #00AEEF;color: #ffffff;">
                            <th width="20%">Description of Goods</th>
                            <th width="20%">Part Code</th>
                            <th width="10%" align="center">Make</th>
                            <th width="10%" align="center">HSN/SAC</th>
                            <th width="10%" align="center">Quantity</th>
                            <th width="10%" align="center">Rate</th>
                            <th width="5%" align="center">Per</th>
                            <th width="15%" align="center">Amount</th>
                        </tr>
                        <tbody ng-repeat="(item_key,item_value) in order_item_pdf">
                            <tr ng-repeat="(item_data_key,item_data_value) in item_value">
                                <td style="border-bottom: medium none;"><strong>{%item_data_value.name_description%}</strong></td>
                                <td style="border-bottom: medium none;">{%item_data_value.model_no%}</td>
                                <td style="border-bottom: medium none;" align="center">{%item_key.split(' ')[0]%}</td>
                                <td style="border-bottom: medium none;" align="center">{%item_data_value.hsn_code%}</td>
                                <td style="border-bottom: medium none;" align="right"><strong>{%item_data_value.qty%} Nos</strong></td>
                                <td style="border-bottom: medium none;" align="center">{%item_data_value.unit_value%}</td>
                                <td style="border-bottom: medium none;" align="right"> Nos</td>
                                <td style="border-bottom: medium none;" align="right"><strong>{%item_data_value.total_value%}</strong></td>
                            </tr>
                        </tbody>
                        <tr>
                            <td><br></td>
                            <td><br></td>
                            <td><br></td>
                            <td><br></td>
                            <td><br></td>
                            <td><br></td>
                            <td><br></td>
                            <td style="border-top:1px solid #333;" align="right">{%sales_order_data.total_amount%}</td>
                        </tr>
                        <tr>
                            <td><br></td>
                            <td><strong>Freight</strong></td>
                            <td><br></td>
                            <td><br></td>
                            <td><br></td>
                            <td><br></td>
                            <td><br></td>
                            <td align="right">{%sales_order_data.fright%}</td>
                        </tr>
                        <tr>
                            <td><br></td>
                            <td><strong>Pkg & Fwd</strong></td>
                            <td><br></td>
                            <td><br></td>
                            <td><br></td>
                            <td><br></td>
                            <td><br></td>
                            <td align="right">{%sales_order_data.pkg_fwd%}</td>
                        </tr>
                        <tr ng-if="(sales_order_data.igst == true)">
                            <td><br></td>
                            <td><strong>Integrated Tax (IGST)</strong></td>
                            <td><br></td>
                            <td><br></td>
                            <td><br></td>
                            <td><br></td>
                            <td><br></td>
                            <td align="right"><strong>{{$sales_order_data['tax_subtotal'] + $sales_order_data['total_tax_amount']}}</strong></td>
                        </tr> 
                        <tr ng-if="(sales_order_data.igst == false)">
                            <td><br></td>
                            <td><strong>Central Tax (CGST)</strong></td>
                            <td><br></td>
                            <td><br></td>
                            <td><br></td>
                            <td><br></td>
                            <td><br></td>
                            <td align="right"><strong>
                            {{($sales_order_data['tax_subtotal'] + $sales_order_data['total_tax_amount'])/2}}</strong></td>
                        </tr>
                        <tr ng-if="(sales_order_data.igst == false)">
                            <td><br></td>
                            <td><strong>State Tax (SGST)</strong></td>
                            <td><br></td>
                            <td><br></td>
                            <td><br></td>
                            <td><br></td>
                            <td><br></td>
                            <td align="right"><strong>{{($sales_order_data['tax_subtotal'] + $sales_order_data['total_tax_amount'])/2}}</strong></td>
                        </tr>
                        <tr>
                            <td><br></td>
                            <td>Round Off</td>
                            <td><br></td>
                            <td><br></td>
                            <td><br></td>
                            <td><br></td>
                            <td><br></td>
                            <td align="right"><strong>{% sales_order_data.round_off%}</strong></td>
                        </tr>
                        <tr>
                            <td><br></td>
                            <td><strong>Total</strong></td>
                            <td><br></td>
                            <td><br></td>
                            <td><strong>{%sales_order_data.total_qty%} Nos</strong></td>
                            <td><br></td>
                            <td><br></td>
                            <td align="right"><strong>{%sales_order_data.grandTotal%}</strong></td>
                            
                        </tr>
                    </table>
                </div>
                <br style="clear: both">
                <div style="display: inline-block;width: 100%;margin-bottom: 30px">
                    <table class="border-none so_view" style="width: 100%">
                        <tr>
                            <td>Amount Chargeable (in words)</td>
                            <td style="text-align: right;">E. & O.E</td>
                        </tr>
                        <tr>
                            <td colspan="2">
                                <strong>INR {%sales_order_data.total_in_word%} Rupees</strong>
                            </td>
                        </tr>
                    </table>
                </div> 
                <div style="display: inline-block;width: 100%;" class="table-responsive">
                    <table style="width: 100%" class="so_view">
                        <tr>
                            <td width="60%">HSN/SAC</td>
                            <td width="10%">Taxable</td>
                            <td colspan="2" ng-if="(sales_order_data.igst == true)" width="20%">Integrated Tax</td>
                            <td colspan="2" ng-if="(sales_order_data.igst == false)" width="10%">Central Tax</td>
                            <td colspan="2" ng-if="(sales_order_data.igst == false)" width="10%">State Tax</td>
                            <td width="10%">Total</td>
                        </tr>
                        <tr>
                            <td></td>
                            <td >value</td>
                            <td ng-if="(sales_order_data.igst == true)">Rate</td>
                            <td ng-if="(sales_order_data.igst == true)">Amount</td>
                            <td ng-if="(sales_order_data.igst == false)">Rate</td>
                            <td ng-if="(sales_order_data.igst == false)">Amount</td>
                            <td ng-if="(sales_order_data.igst == false)">Rate</td>
                            <td ng-if="(sales_order_data.igst == false)">Amount</td>
                            <td>Tax Amount</td>
                        </tr>
                        <tbody ng-repeat="(key,value) in hsn_codes">
                            <tr ng-if="(sales_order_data.igst == true)" class="hsn_tr">
                                <td>{%value.hsn_code%}</td>
                                <td>{%value.total_hsn_value%}</td>
                                <td>18%</td>
                                <td>{% (value.total_hsn_value*18)/100 %}</td>
                                <td>{% (value.total_hsn_value*18)/100 %}</td>
                            </tr> 
                            <tr ng-if="(sales_order_data.igst == false)" class="hsn_tr">
                                <td>{%value.hsn_code%}</td>
                                <td>{%value.total_hsn_value%}</td>
                                <td>9%</td>
                                <td>{% (value.total_hsn_value*9)/100 %}</td>
                                <td>9%</td>
                                <td>{% (value.total_hsn_value*9)/100 %}</td>
                                <td>{% (value.total_hsn_value*18)/100 %}</td>
                            </tr>
                        </tbody> 
                        <tr style="border-top:medium none;" ng-if="(sales_order_data.igst == true)">
                            <td>996511</td>
                            <td>{% sales_order_data.fright_pkg_fwd_hsn %}</td>
                            <td>18%</td>
                            <td>{% sales_order_data.igst_fright_hsn %}</td>
                            <td>{% sales_order_data.igst_fright_hsn %}</td>
                        </tr> 
                        <tr style="border-top:medium none;" ng-if="(sales_order_data.igst == false)">
                            <td>996511</td>
                            <td>{%sales_order_data.fright_pkg_fwd_hsn%}</td>
                            <td>9%</td>
                            <td>{% sales_order_data.cgst_sgst_fright_hsn %}</td>
                            <td>9%</td>
                            <td>{% sales_order_data.cgst_sgst_fright_hsn %}</td>
                            <td>{% sales_order_data.igst_fright_hsn %}</td>
                        </tr>
                        </tr>    
                        <tr ng-if="(sales_order_data.igst == true)" class="igst_tr">
                            <td>Total</td>
                            <td>{%sales_order_data.total_taxable_value%}</td>
                            <td></td>
                            <td>{%sales_order_data.igst_total%}</td>
                            <td>{%sales_order_data.hsn_grand_total %}</td>
                        </tr>
                        <tr ng-if="(sales_order_data.igst == false)" class="igst_tr">
                            <td>Total</td>
                            <td>{%sales_order_data.total_taxable_value%}</td>
                            <td>9%</td>
                            <td>{%sales_order_data.cgst_sgst_total | number:2%}</td>
                            <td></td>
                            <td>{%sales_order_data.cgst_sgst_total | number:2%}</td>
                            <td>{%sales_order_data.hsn_grand_total %}</td>
                        </tr> 
                        <tr>
                            <td height="auto">
                                Company's PAN: <strong>{%sales_order_data.pan_no%}</strong>
                                <br>
                                GST No:<strong>{%sales_order_data.gst_no%}</strong>
                            </td>
                            <td rowspan="3" colspan="4" align="right" ng-if="(sales_order_data.igst == true)">
                                <br>
                                <br>
                                <br>
                                <br>
                                <br>
                                <br>
                                For Triton Process Automation Pvt Ltd
                                <br>
                                <br>
                                <br>
                                <br>
                                <br>
                                <br>
                                <br>
                                <br>
                                <br>
                                <br>
                                Authorised Signatory
                            </td>
                            <td rowspan="3" colspan="6" align="right" ng-if="(sales_order_data.igst == false)">
                                <br>
                                <br>
                                <br>
                                <br>
                                <br>
                                <br>
                                For Triton Process Automation Pvt Ltd
                                <br>
                                <br>
                                <br>
                                <br>
                                <br>
                                <br>
                                <br>
                                <br>
                                <br>
                                <br>
                                Authorised Signatory
                            </td>
                        </tr>
                        <tr>
                            <td>
                                Bank Name: Kotak Mahindra Bank Limited
                                <br>
                                A/c No: 0411491015
                                <br>
                                Branch & IFS Code: Ghatkopar West & KKBK0000682
                                <br>
                                Pay in the favor of: Triton Process Automation Pvt Ltd.
                            </td>
                        </tr>
                        <tr>
                            <td>
                                Remark:
                                <br>
                                1. In Case of any discrepancy in the invoice,please bring the same to our attention in 7days of receipt of invoice.
                                <br>
                                2.Delay in payment beyond the agreedc credit period will attract interest @18%
                                <br>
                                3.Government Taxes applied as Per the prevailing rates.
                            </td>
                        </tr>
                    </table>
                </div>       
                <div class="modal-footer">
                    <button class="btn btn-primary" type="button" ng-click="soc.modalOk()">OK</button>
                    <button class="btn btn-warning" type="button" ng-click="soc.modalCancel()">Cancel</button>
                </div>
            </div>
        </script> 
    </div>
    <div class="row">
        <div class="col-md-12 mb-30">
            <div class="card">
                <div class="row">
                    <div class="col-md-12">
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group" id="payment_terms_error_div"> 
                                    <div class="col-md-12">Payment Terms<sup class="text-danger">*</sup></div>
                                    <div class="col-md-12">
                                        <?= Form::text('payment_terms', old('payment_terms'),array('class' => 'form-control','id'=>'payment_terms','ng-model'=>'salesorder.payment_terms','placeholder'=>'Payment Terms')) ?>
                                            <div id="payment_terms_box"></div>
                                        <span id="payment_terms_error" class="help-inline text-danger"><?=$errors->first('payment_terms')?></span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group " id="delivery_error_div">
                                    <div class="col-md-12">Delivery<sup class="text-danger">*</sup></div>
                                    <div class="col-md-12">
                                        <?= Form::text('delivery', old('delivery'),array('class' => 'form-control','placeholder'=>'Delivery','ng-model'=>'salesorder.delivery')) ?>
                                        <span id="delivery_error" class="help-inline text-danger"><?=$errors->first('delivery')?></span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group " id="advanced_received_error_div">
                                    <div class="col-md-12">Advanced Received<sup class="text-danger">*</sup></div>
                                    <div class="col-md-12">
                                        <?= Form::text('advanced_received', old('advanced_received'),array('class' => 'form-control select2 number_only','id'=>'advanced_received','placeholder'=>'Advanced Received','ng-model'=>'salesorder.advanced_received')) ?>
                                        <span id="advanced_received_error" class="help-inline text-danger"><?=$errors->first('advanced_received')?></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group " id="part_shipment_error_div">
                                    <div class="col-md-12">Part Shipment<sup class="text-danger">*</sup></div>
                                    <div class="col-md-12">
                                        <?= Form::select('part_shipment',config('Constant.part_shipment'),old('part_shipment'),array('class' => 'form-control','placeholder'=>'Part Shippment','id'=>'part_shipment','ng-model'=>'salesorder.part_shipment')) ?>
                                        <span id="part_shipment_error" class="help-inline text-danger"><?=$errors->first('part_shipment')?></span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group " id="trasport_error_div">
                                    <div class="col-md-12">Transport<sup class="text-danger">*</sup></div>
                                    <div class="col-md-12">
                                        <?= Form::select('trasport',config('Constant.trasport'),old('trasport'),array('class' => 'form-control','placeholder'=>'Transport','id'=>'trasport','ng-model'=>'salesorder.trasport')) ?>
                                        <span id="trasport_error" class="help-inline text-danger"><?=$errors->first('trasport')?></span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                               <div class="form-group " id="pkg_fwd_error_div">
                                    <div class="col-md-12">PKG and FWD<sup class="text-danger">*</sup></div>
                                    <div class="col-md-12">
                                        <?= Form::text('pkg_fwd', old('pkg_fwd'),array('class' => 'form-control number_only','id'=>'pkg_fwd','placeholder'=>'PKG and FWD','ng-model'=>'salesorder.pkg_fwd','ng-change'=>'updateValue()')) ?>
                                        <span id="pkg_fwd_error" class="help-inline text-danger"><?=$errors->first('pkg_fwd')?></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>    
                <div class="row">
                    <div class="col-md-12">
                        <div class="row">
                            <div class="col-md-4">
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <div class="col-md-12">Other Expense</div>
                                            <div class="col-md-12">
                                                <?= Form::text('other_expense', old('other_expense'),array('class' => 'form-control select2 number_only','id'=>'other_expense','placeholder'=>'Other Expense','ng-model'=>'salesorder.other_expense','ng-change'=>'updateValue()')) ?>
                                                <span id="select_2" class="help-inline text-danger"><?=$errors->first('other_expense')?></span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                       <div class="form-group " id="reason_for_other_expense_error_div">
                                            <div class="col-md-12">Reason For Other Expense</div>
                                            <div class="col-md-12">
                                                <?= Form::textarea('reason_for_other_expense',old('reason_for_other_expense'),array('class' => 'form-control','placeholder'=>'Reason For Other Expense','rows'=>'6','ng-model'=>'salesorder.reason_for_other_expense')) ?>
                                                <span id="reason_for_other_expense_error" class="help-inline text-danger"><?=$errors->first('reason_for_other_expense')?></span>
                                            </div>
                                        </div> 
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group " id="fright_error_div">
                                            <div class="col-md-12">Freight<sup class="text-danger">*</sup></div>
                                            <div class="col-md-12">
                                                <?= Form::text('fright', old('fright'),array('class' => 'form-control number_only','placeholder'=>'Freight','ng-model'=>'salesorder.fright','ng-change'=>'updateValue()')) ?>
                                                <span id="fright_error" class="help-inline text-danger"><?=$errors->first('fright')?></span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <div class="col-md-12">Remarks</div>
                                            <div class="col-md-12">
                                                <?= Form::textarea('remarks',old('remarks'),array('class' => 'form-control','placeholder'=>'Remarks','rows'=>'6','ng-model'=>'salesorder.remarks')) ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                               <div class="form-group " id="image_error_div">
                                    <div class="col-md-12">File<sup class="text-danger">*</sup></div>
                                    <div class="col-md-12">
                                        <div class="inputfile-box">
                                             <input class="inputfile" id="file" type="file" ng-file-model="salesorder.product_image" name="product_image">

                                             <label for="file"><span class="file-box" id="file-name"></span><span class="file-button">Browse</span></label>
                                              @foreach(json_decode($sales_order['image'],true) as $value )
                                             <a href='<?= LOCAL_IMAGE_PATH."salesorder/".$value ?>' target="_blank">View</a>
                                              @endforeach  
                                             <span id="product_image_error" class="help-inline text-danger"><?=$errors->first('image')?></span>
                                         </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12 mb-30">
            <div class="card">
            <div class="row">
                <div class="col-md-12">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group" id="tax_subtotal_error_div">
                                <div class="col-md-12">Tax On Subtotal</div>
                                <div class="col-md-12">
                                    <?= Form::text('tax_subtotal', old('tax_subtotal'),array('class' => 'form-control select2 number_only','id'=>'tax_subtotal','placeholder'=>'Tax On Subtotal','ng-model'=>'salesorder.tax_subtotal','ng-change'=>'updateValue()','readonly'=>true)) ?>
                                    <span id="tax_subtotal_error" class="help-inline text-danger"><?=$errors->first('tax_subtotal')?></span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group @if($errors->has('grand_total')) has-error @endif">
                                <div class="col-md-12">Grand Total</div>
                                <div class="col-md-12">
                                    <?= Form::text('grand_total', old('grand_total'),array('class' => 'form-control','placeholder'=>'Grand Total','ng-model'=>'salesorder.grand_total','readonly'=>true)) ?>
                                    
                                    <span id="select_2" class="help-inline text-danger"><?=$errors->first('grand_total')?></span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                </div>
            </div>
        </div>
    </div>        
    <div class="text-right">
        @if($is_approve == true && $is_show_approve_btn == true && $is_show_hold_btn == true)
            <button type="button" name="save_button" ng-click="soc.openSoView()" value="approve" class="btn btn-primary btn-sm disabled-btn" title="Save & Add New user">Generate SO & View</button>
            <button type="button" name="save_button" ng-click="soc.completeSalesOrder('on_hold')" value="on_hold" class="btn btn-primary btn-sm disabled-btn" title="Save & Add New user">On Hold</button>
            <button type="button" name="save_button" ng-click="soc.completeSalesOrder('approve')" value="approve" class="btn btn-primary btn-sm disabled-btn" title="Save & Add New user">Generate SO & Send</button>
            <a href="<?=URL::route('salesorder.index')?>" class="btn btn-default btn-sm" title="Back to user Page">Cancel</a>
        @else
            @if($is_approve == false)
                <button type="button" name="save_button" ng-click="soc.completeSalesOrder('save')" value="save" class="btn btn-primary btn-sm disabled-btn" title="Save & Add New user">Save</button>
                <button type="button" name="save_button" ng-click="soc.completeSalesOrder('save_exit')" value="save_exit" class="btn btn-primary btn-sm disabled-btn" title="Save and exit">Save & exit</button>
                <a href="<?=URL::route('salesorder.index')?>" class="btn btn-default btn-sm" title="Back to user Page">Cancel</a>
            @elseif($is_show_approve_btn == false)
                <button type="button" name="save_button" ng-click="soc.openSoView()" value="approve" class="btn btn-primary btn-sm disabled-btn" title="Save & Add New user">Generate SO & View</button>
                <button type="button" name="save_button" ng-click="soc.completeSalesOrder('on_hold')" value="on_hold" class="btn btn-primary btn-sm disabled-btn" title="Save & Add New user">On Hold</button>
                <button type="button" name="save_button" ng-click="soc.completeSalesOrder('approve')" value="approve" class="btn btn-primary btn-sm disabled-btn" title="Save & Add New user">Generate SO & Send</button>
                <a href="<?=URL::route('salesorder.index')?>" class="btn btn-default btn-sm" title="Back to user Page">Cancel</a>
            @elseif($is_show_hold_btn == false)    
                <button type="button" name="save_button" ng-click="soc.openSoView()" value="approve" class="btn btn-primary btn-sm disabled-btn" title="Save & Add New user">Generate SO & View</button>
                <button type="button" name="save_button" ng-click="soc.completeSalesOrder('approve')" value="approve" class="btn btn-primary btn-sm disabled-btn" title="Save & Add New user">Generate SO & Send</button>
                <a href="<?=URL::route('salesorder.index')?>" class="btn btn-default btn-sm" title="Back to user Page">Cancel</a>
            @endif    
        @endif
        
    </div>
</div>
@stop

@section('end_form')
<?=Form::close()?>
@stop
 
@section('style')
    <?=Html::style('backend/css/jquery.fileuploader.css')?>
    <?=Html::style('backend/css/jquery.fileuploader-theme-thumbnails.css')?>
    <?=Html::style('backend/css/bootstrap-fileupload.css')?>
    <?=Html::style('backend/css/datepicker.css',[], IS_SECURE)?>
@stop
@section('script')
    <?= Html::script('backend/js/select2.min.js',[],IS_SECURE) ?>
    <?=Html::script('backend/js/bootstrap-datepicker.js', [], IS_SECURE)?>
    <?=Html::script('backend/js/jquery.form.min.js', [], IS_SECURE)?>
    <?= Html::script('backend/js/angular.min.js',[],IS_SECURE) ?>
    <?= Html::script('backend/js/ui.bootstrap.min.js',[],IS_SECURE) ?>
    <?= Html::script('backend/js/underscore.min.js',[],IS_SECURE) ?>
    <?= Html::script('backend/js/angular-file-upload.min.js',[],IS_SECURE) ?>
    <?= Html::script('backend/js/jquery-migrate-1.2.1.min.js',[],IS_SECURE) ?>
    <?= Html::script('backend/js/bootstrap-fileupload.js',[],IS_SECURE) ?>

    <script type="text/javascript">
        $('#city_loader').hide();
        $('#state_loader').hide();
        $('#loader').hide();
        var productData = '{!! $product_data !!}';
        var supplierData = JSON.parse('{!! $supplier_data !!}');
        
        var sales_order = JSON.parse('{!! $sales_order !!}');
        var sales_order_item = JSON.parse('{!! $sales_order_item !!}');

        var url = "<?= URL::route('salesorder.update',$id)?>";
        var method = "patch";
        var msg = "Record Updated successfully";
        var fileurl = "<?= URL::route('file.upload')?>";
        var getproducturl = "<?= URL::route('salesorder.getproducts')?>";
        var so_no = "<?= $sono ?>";
        var removeproducturl = "<?= URL::route('salesorder.removeproducts',$id)?>";
        var getsupplierproducturl = "<?= URL::route('salesorder.getSupplierProducts')?>";
        var redirect_url = "<?= route('salesorder.index')?>";
        var approve_url = "<?= route('salesorder.approval.update',$id)?>"
        var on_hold_url = "<?= route('salesorder.onhold.update',$id)?>"
        var x = document.getElementById("billing_title");
        var option = document.createElement("option");
        var selected_option = $('#company_id').val();
        //view
        var sales_order_data = JSON.parse('{!! $sales_order_data !!}');
        var order_item_pdf = JSON.parse('{!! $order_item_pdf !!}');
        var hsn_codes = JSON.parse('{!! $hsn_codes !!}');
        var country_id = '';
        var state_id = '';
        var city_id = '';
        if(selected_option != ''){
            x.add(option, x[0]);
        }
        // console.log($("#sales_person_id option[value='']").text());
        // if(($("#sales_person_id option[value='']").text()) == null){
        //     $(this).remove();
        // }
        // 
        //
        
        $('#billingaddress').val()
        $('#trasport').select2({
            placeholder:'Select Transport',
        });
        $('#part_shipment').select2({
            placeholder:'Select Part Shipment',
        });
        $('#billing_title').select2({
            placeholder:'Select Billing Address',
        });
        $('#company_id').select2({
            placeholder:'Select Company',
        });
        $('#countryid').select2({
            placeholder:'Select Country',
        });
        $('#stateid').select2({
            placeholder:'Select State',
        });
        $('#cityid').select2({
            placeholder:'Select City',
        });
        $('#bill_state_id').select2({
            placeholder:'Select State',
        });
        $('#bill_city_id').select2({
            placeholder:'Select City',
        });
        $('#bill_country_id').select2({
            placeholder:'Select Country',
        });
        $('#products').select2({
            placeholder:'Select Product',
        });
        $('#customer_id').select2({
            placeholder:'Select Customer',
        });
        $('#supplier').select2({
            placeholder:'Select Supplier',
        });
        $('#supplierForMulti').select2({
            placeholder:'Select Supplier',
        });
        $('#manu_clearance').select2({
            placeholder:'Select Manu. Clearance',
        });
        
        
        var token = "{{csrf_token()}}";
        function closeModel()
        {
            $("[id$='error_span']").empty();
            $("[id$='_errordiv']").removeClass('has-error');
            $('#billing_title').val('').trigger('change');
            $('#billing_address_form')[0].reset();
        }
        function getBillingAddress(val){
            $.ajax({
            async      : false,
            url        : '{{ URL::route('search.getbillingaddress')}}',
            type       : 'post',
            data       : { "customer_id": val , '_token' : token },
            dataType   : 'html',
            success    : function(billingaddress) {
                    $("#billing_title").val('').trigger('change');
                    var billingaddress = $(billingaddress).html();
                    $("#billing_title").html(billingaddress);
                }
            });
        }
        function getCustomerInfo(val){
            $.ajax({
            async      : false,
            url        : '{{ URL::route('admin.salesorder.getcustomerinfo')}}',
            type       : 'post',
            data       : { "customer_id": val , '_token' : token },
            success    : function(data) {
                    var contact_no = data.person_phone.split(',');
                    var person_email = data.person_email.split(',');
                    $('#customer_contact_no').val(contact_no[0]).trigger('change');
                    $('#customer_contact_email').val(person_email[0]).trigger('change');
                    $('#customer_contact_name').val(data.person_name).trigger('change');
                }
            });
        }
        function getCities(val){

            var token = "{{csrf_token()}}";
            $.ajax({
            async      : false,
            url        : '{{ URL::route('search.getcity')}}',
            type       : 'post',
            data       : { "state_id": val , '_token' : token },
            dataType   : 'html',
            success    : function(cities) {
                    $("#cityid").val('').trigger('change');
                    var city_options = $(cities).html();
                    $("#cityid").html(city_options);
                    if(city_id != null){
                        $("#cityid").val(city_id).trigger('change');
                    }
                }
            });
        }
        function getStates(val){

            var token = "{{csrf_token()}}";
            $.ajax({
            async      : false,
            url        : '{{ URL::route('search.getstate')}}',
            type       : 'post',
            data       : { "country_id": val , '_token' : token },
            dataType   : 'html',
            success    : function(states) {
                    $("#stateid").val('').trigger('change');

                    var state_options = $(states).html();
                    $("#stateid").html(state_options);
                    if(state_id != null){
                        $("#stateid").val(state_id).trigger('change');
                    }
                }
            });
        }
        function getBillingCities(val){
            var token = "{{csrf_token()}}";
            $.ajax({
            async      : false,
            url        : '{{ URL::route('search.getcity')}}',
            type       : 'post',
            data       : { "state_id": val , '_token' : token },
            dataType   : 'html',
            success    : function(cities) {
                    $("#bill_city_id").val('').trigger('change');
                    var city_options = $(cities).html();
                    $("#bill_city_id").html(city_options);
                }
            });
        }
        function checkedBillingAddress(val){
            // if(sales_order.billing_id != null){
            //     val = sales_order.billing_id;
            // }
            $.ajax({
                async: false,
                type: "POST",
                url: '{{ URL::route('admin.salesorder.checkedbillingaddress')}}',
                data:{ "billing_id": val , '_token' : token },
                success: function(data){
                    state_id = data.state_id;
                    city_id = data.city_id;
                    $('#shipping_address').val(data.address);
                    $('#countryid').val(data.country_id).trigger('change');
                    $('#pin_code').val(data.pincode);
                }
            });
        }
        function getBillingStates(val){
            var token = "{{csrf_token()}}";
            $.ajax({
            async      : false,
            url        : '{{ URL::route('search.getstate')}}',
            type       : 'post',
            data       : { "country_id": val , '_token' : token },
            dataType   : 'html',
            success    : function(states) {
                    $("#bill_state_id").val('').trigger('change');
                    var state_options = $(states).html();
                    $("#bill_state_id").html(state_options);
                }
            });
        }
        function selectPaymentTerm(payment_terms) {
            $("#payment_terms").val(payment_terms).trigger('change');
            $("#payment_terms_box").hide();
        }
        $(document).ready(function(){
            if($("#company_id").children().first().text() != null){
                $("#company_id").children().first().remove();
            }if($("#customer_id").children().first().text() != null){
                $("#customer_id").children().first().remove();
            }if($("#billing_title").children().first().text() != null){
                $("#billing_title").children().first().remove();
            }if($("#stateid").children().first().text() != null){
                $("#stateid").children().first().remove();
            }if($("#cityid").children().first().text() != null){
                $("#cityid").children().first().remove();
            }
            $('#company_id').attr('disabled','true');
            var token = "{{csrf_token()}}";
            //autocomplete
            // $("#search-box").keyup(function(){
            //     console.log($(this).val());
            //     $.ajax({
            //     type: "POST",
            //     url: '{{ URL::route('admin.salesorder.getshippingaddress')}}',
            //     data:{ "shippingaddress": $(this).val() , '_token' : token },
            //     beforeSend: function(){
            //         // $("#search-box").css("background","#FFF url(LoaderIcon.gif) no-repeat 165px");
            //     },
            //     success: function(data){
            //         $("#suggesstion-box").show();
            //         $("#suggesstion-box").html(data);
            //         $("#search-box").css("background","#FFF");
            //     }
            //     });
            // });
            $('#payment_terms_box').hide();
            $("#payment_terms").keyup(function(){
                $.ajax({
                type: "POST",
                url: '{{ URL::route('admin.salesorder.getpaymentterms')}}',
                data:{ "payment_terms": $(this).val() , '_token' : token },
                beforeSend: function(){
                    // $("#search-box").css("background","#FFF url(LoaderIcon.gif) no-repeat 165px");
                },
                success: function(data){
                    $("#payment_terms_box").show();
                    $("#payment_terms_box").html(data);
                    $("#payment_terms").css("background","#FFF");
                }
                });
            });
            var currentDate = new Date();  
            $('#order_date').datepicker({
                format: "dd-mm-yyyy",
                autoclose: true,
                todayHighlight: true,
                endDate:currentDate,
            });
        });       
        // $('#check_billing').val('0');
        if($('#check_billing').is(':checked') == true){
            $('#check_billing').val('1');
            $('#check_billing').attr('checked', true);
            $('.shipping_address').attr('readonly','true');
            $('#state_id').attr('disabled','true');
            $('#city_id').attr('disabled','true');
            $('#areaname').attr('readonly','true');
            $('#countryid').attr('readonly','true');
            $('#pincode').attr('readonly','true');
        }
        if(sales_order['check_billing'] == '1'){
            $('.shipping_address').attr('readonly','true');
                $('#stateid').attr('disabled','true');
                $('#cityid').attr('disabled','true');
                $('#areaname').attr('readonly','true');
                $('#countryid').attr('disabled','true');
                $('#pin_code').attr('readonly','true');
        }
        $('#billing_title').change(function(){
            if($('#check_billing').is(':checked') == true){
                checkedBillingAddress($('#billing_title').val());
            }
        });

        $('#check_billing').change(function(){
            if(this.checked){
                if($('#billing_title').val() != '? undefined:undefined ?'){
                    checkedBillingAddress($('#billing_title').val());
                }
                $('#check_billing').val('1');
                $('#check_billing').prop('checked', true);
                $('.shipping_address').attr('readonly','true');
                $('#stateid').attr('disabled','true');
                $('#cityid').attr('disabled','true');
                $('#areaname').attr('readonly','true');
                $('#countryid').attr('disabled','true');
                $('#pin_code').attr('readonly','true');
            }else{
                $('#check_billing').val('0');
                $('#check_billing').prop('checked', false);
                $('.shipping_address').removeAttr('readonly');
                $('#stateid').removeAttr('disabled');
                $('#cityid').removeAttr('disabled');
                $('#countryid').removeAttr('disabled');
                $('#pin_code').removeAttr('readonly');
                $('#areaname').removeAttr('readonly');
                $('#areaname').val('');
                $('#pin_code').val('').trigger('change');
                $('.shipping_address').val('');
                $('#cityid').val('').trigger('change');
                $('#stateid').val('').trigger('change');
                $('#countryid').val('').trigger('change');
            }
        })
        $("#stateid").on('change',function() {
            getCities($(this).val());
        });
        $("#countryid").on('change',function() {
            getStates($(this).val());
        });
        $("#bill_state_id").on('change',function() {
            getBillingCities($(this).val());
        });
        $("#bill_country_id").on('change',function() {
            getBillingStates($(this).val());
        });
        
        
        $("#customer_id").on('change',function() {
            
            $('#billing_customer_id').val($(this).val());
            getBillingAddress($(this).val());
            getCustomerInfo($(this).val());

            // getProducts($(this).val());
        });
        
        $(document).ready(function(){
            var check_select = $('.card').find('.select2');

            $.each(check_select,function(k,v){
                $.each($(v).find('option'),function(i_k,i_v){
                    if (i_v.text == '') {
                        $(i_v).remove();
                    }
                });
            });
            
            var filename = (sales_order['image']).split('_');
            $('#file-name').text(filename[1]);

            $('#file').change(function(){
                $('#file-name').text($(this)[0].files[0].name);
            });
        });
    </script>
    <?= Html::script('backend/js/app.js',[],IS_SECURE) ?>
@stop
