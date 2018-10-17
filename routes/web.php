<?php
use App\Models\ProductMaster;
use App\Models\ManageStock;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
Route::get('temp','Admin\RouteController@test');

Route::get('datainsert',function(){
	$product_data = ProductMaster::select('id as product_id','name_description as name_description','model_no as model_no','qty as total_qty','qty as total_physical_qty','company_id as company_id','supplier_id as supplier_id')->get()->toArray();
	foreach ($product_data as $key => $value) {
	$managestock = new ManageStock();
		$managestock->fill($value);
		$managestock->save();
	}
});

// Route::get('/demo','DemoController@demo');
//store all routes of application
Route::get('routes',[
	'as' => 'show.routes',
	'uses'=>'Admin\RouteController@storeRouteList'
]);
//access denied route
Route::get('/access-denied',function(){
    return view('admin.access_denied');
})->name('access.denied');
Route::get('/',function(){
	return redirect()->route('admin.login');
});
Route::get('/admin', function () {
    return view('admin.login');
});
/*----------Admin Route ---------- */ 


Route::get('admin/login', 'Admin\Auth\AdminLoginController@showLoginForm')->name('admin.login');
Route::post('admin/login', 'Admin\Auth\AdminLoginController@login')->name('admin.login');

Route::resource('admin/xml', 'tallyController');
Route::post('admin/password/email', 'Admin\Auth\AdminForgotPasswordController@sendResetLinkEmail')->name('password.email.admin');
Route::get('admin/password/reset/{token?}', 'Admin\Auth\AdminResetPasswordController@showResetForm')->name('password.reset');
Route::post('admin/password/reset', 'Admin\Auth\AdminResetPasswordController@reset')->name('password.reset.post');
Route::post('admin/password/change', 'Admin\Auth\AdminChnagePasswordController@changepassword')->name('password.change.post');
Route::group(array('middleware' => ['admin_guest']), function () {
		//designation master
		Route::get('admin/designation/export','Admin\DesignationController@export')->name('designation.export');
		/**Start Route for logout**/
		Route::get('admin/logout', 'Admin\Auth\AdminLoginController@logout')->name('admin.logout');

			// SYSTEM USER //
		Route::resource('admin/designation', 'Admin\DesignationController',['except' => 'destroy']);
	
		Route::post('designation/delete', 'Admin\DesignationController@destroy')->name('designation.delete');
		
		Route::post('admin/systemuser/getdesignation','Admin\SystemUserController@getdesignation')->name('systemuser.getdesignation');
		
		Route::post('admin/systemuser/getpermissionlist','Admin\SystemUserController@getPermissionList')->name('systemuser.getpermissionlist');
		Route::get('admin/systemuser/export','Admin\SystemUserController@export')->name('systemUser.export');
		
		
		//sales order ajax
		Route::post('admin/salesorder/getshippingaddress', 'Admin\SalesOrderController@getshippingaddress')->name('admin.salesorder.getshippingaddress');
		Route::post('admin/salesorder/checkedbillingaddress', 'Admin\SalesOrderController@checkedBillingAddress')->name('admin.salesorder.checkedbillingaddress');
		Route::post('admin/salesorder/getSoNo', 'Admin\SalesOrderController@getSoNo')->name('admin.salesorder.getSoNo');
		Route::post('admin/salesorder/getfile', 'Admin\SalesOrderController@getFile')->name('file.upload');
		Route::post('/admin/salesorder/getProducts','Admin\SalesOrderController@getProducts')->name('salesorder.getproducts');
		Route::post('/admin/salesorder/getSupplierProducts','Admin\SalesOrderController@getSupplierProducts')->name('salesorder.getSupplierProducts');
		Route::post('admin/salesorder/getpaymentterms', 'Admin\SalesOrderController@getPaymentTerms')->name('admin.salesorder.getpaymentterms');
		Route::patch('admin/salesorder/{id}/approvalupdate', 'Admin\SalesOrderController@approvalUpdate')->name('salesorder.approval.update');
		Route::patch('admin/salesorder/{id}/onholdupdate', 'Admin\SalesOrderController@onholdUpdate')->name('salesorder.onhold.update');
		Route::get('admin/pdfgenerate', 'Admin\SalesOrderController@pdfgenerate')->name('salesorder.pdfgenerate');
		Route::post('/admin/salesorder/{id}/removeProducts','Admin\SalesOrderController@removeProducts')->name('salesorder.removeproducts');
		Route::post('admin/salesorder/getcustomerinfo', 'Admin\SalesOrderController@getCustomerInfo')->name('admin.salesorder.getcustomerinfo');
		Route::get('/admin/salesorder/reorder/{id}','Admin\SalesOrderController@reOrder')->name('salesorder.reorder');
		Route::post('/admin/salesorder/reorderstore','Admin\SalesOrderController@reOrderStore')->name('salesorder.reorderstore');
		Route::get('/admin/salesorder/export','Admin\SalesOrderController@export')->name('salesorder.export');
		Route::post('/admin/salesorder/soview','Admin\SalesOrderController@soView')->name('salesorder.soview');
		Route::post('admin/salesorder/getcustomer', 'Admin\SalesOrderController@getCustomerSupplier')->name('admin.salesorder.getcustomer');

		//product master
		Route::get('admin/product/export','Admin\ProductMasterController@export')->name('product.export');
		Route::get('admin/product/xml/{id}','Admin\ProductMasterController@exportxml')->name('product.xml');

		//purchase requisition
		Route::get('admin/purchase-requisition/export','Admin\PurchaseRequisitionController@export')->name('purchase.requisition.export');

		// re order purchase requisition
		Route::get('/admin/purchase-requisition/reorder/{id}','Admin\ReorderController@reorder')->name('reorder.create');
		Route::post('/admin/purchase-requisition/reorder','Admin\ReorderController@create')->name('purchase.store');

		//purchase requisition approval
		Route::get('admin/purchase-requisition-approval/export','Admin\PurchaseRequisitionApprovalController@export')->name('purchase.requisition-approval.export');
		Route::get('admin/pdf/{id}', 'GeneratePdfModelController@showModal')->name('pdf.showModal');
		Route::get('admin/poSaveData', 'GeneratePdfModelController@poDataSave')->name('pdf.datasave');

		// purchase requisition delete
		Route::post('/admin/purchase-requisition-approval/delete','Admin\PurchaseRequisitionApprovalController@delete')->name('purchase-requisition-approval.delete');
		// purchase requistion re-order
		Route::get('/admin/purchase-requisition-approval/reorder','Admin\PurchaseRequisitionApprovalController@reorder')->name('purchase.reorder');
		Route::get('/admin/purchase-requisition-approval/pendings','Admin\PurchaseRequisitionApprovalController@pendingOrders')->name('admin.reorder');

		// purchase requisition re-store deleted.
		Route::post('/admin/purchase-requisition-approval/restore','Admin\PurchaseRequisitionApprovalController@restoreOrders')->name('admin.restore');

		// Email Master.
		Route::get('admin/mails/dashboard','Admin\EmailMasterController@index')->name('emails.index');
		Route::post('/admin/mails/mailtemplate','Admin\EmailMasterController@userEmailTemplate')->name('user.mail.template');

		Route::get('admin/purchase-requisition-approval/getPrItemsValue','Admin\PurchaseRequisitionApprovalController@getPrItemsValue')->name('purchase.requisition-approval.getItemValue');
		Route::get('admin/purchase-requisition-approval/exportPrItemsValue/{id}','Admin\PurchaseRequisitionApprovalController@exportPrItemsValue')->name('purchase.requisition-approval.exportPrItem');

		//supplier master
		Route::post('admin/suppliers/getstate','Admin\SupplierMasterController@getstate')->name('suppliers.getstate');
		Route::post('admin/suppliers/getcity','Admin\SupplierMasterController@getcity')->name('suppliers.getcity');
		Route::get('admin/manufacturer/export','Admin\SupplierMasterController@export')->name('suppliers.export');
		Route::get('admin/manufacturer/xml/{id}','Admin\SupplierMasterController@exportxml')->name('manufacturer.xml');

		//city and state
		Route::post('search/getcity', 'SearchController@getcity')->name('search.getcity');
		Route::post('search/getstate', 'SearchController@getstate')->name('search.getstate');
		Route::get('admin/state/export','Admin\StateController@export')->name('state.export');

		//billing address
		Route::post('search/getbillingaddress', 'SearchController@getbillingaddress')->name('search.getbillingaddress');
		Route::post('billingaddress/store', 'Admin\SalesOrderController@storebillingaddress')->name('admin.billingaddress.store');
		
		//store billing address
		Route::post('billingaddress/store', 'Admin\SalesOrderController@storebillingaddress')->name('admin.billingaddress.store');
		
		//manage stock
		Route::get('admin/managestock/export','Admin\ManageStockController@export')->name('manage_stock.export');
		Route::get('admin/managestock/generatepo','Admin\ManageStockController@generatePo')->name('manage_stock.generatepo');
		Route::get('admin/managestock/jeditable/{id}','Admin\ManageStockController@QtyStore')->name('manage_stock.jeditable');
		Route::get('admin/managestock/fetchpoid/{id}','Admin\ManageStockController@fetchPoId')->name('manage_stock.fetchpoid');
		Route::post('admin/managestock/postore','Admin\ManageStockController@PoStore')->name('manage_stock.postore');
		Route::get('admin/managestock/removepoitem','Admin\ManageStockController@removePoItem')->name('manage_stock.removepoitem');
		Route::get('admin/managestock/randomredirect','Admin\ManageStockController@randomRedirect')->name('manage_stock.randomredirect');
		Route::post('admin/managestock/getsupplier','Admin\ManageStockController@getSupplier')->name('admin.managestock.getsupplier');

		// manage stock v2
		Route::get('admin/managestock/newindex/release/{id}','Admin\ManageStockController@release')->name('block-qty.release');
		Route::post('admin/managestock/jeditable','Admin\ManageStockController@block')->name('block-qty.create');
		
		//company master
		Route::post('admin/companymaster/getcity','Admin\CompanyMasterController@getcity')->name('companymaster.getcity');
		Route::post('admin/companymaster/getdynamiccity','Admin\CompanyMasterController@getdynamiccity')->name('companymaster.getdynamiccity');
		Route::post('admin/companymaster/getdynamicstate','Admin\CompanyMasterController@getdynamicstate')->name('companymaster.getdynamicstate');
		Route::get('admin/companymaster/export','Admin\CompanyMasterController@export')->name('company.export');

		//customer master
		Route::post('admin/customer/getstate','Admin\CustomerMasterController@getstate')->name('customer.getstate');
		Route::post('admin/customer/getcity','Admin\CustomerMasterController@getcity')->name('customer.getcity');
		Route::get('admin/customer/export','Admin\CustomerMasterController@export')->name('customer.export');

		
		//distributor master
		Route::post('admin/distributor/getstate','Admin\DistributorController@getstate')->name('distributor.getstate');
		Route::post('admin/distributor/getcity','Admin\DistributorController@getcity')->name('distributor.getcity');
		Route::get('admin/distributor/export','Admin\DistributorController@export')->name('distributor.export');


		// DISTRIBUTOR MASTER //
		Route::resource('admin/distributor', 'Admin\DistributorController',['except'=>['delete','show']]);
		Route::post('admin/distributor/delete','Admin\DistributorController@delete')->name('distributor.delete');
		
		//cities
		Route::get('/admin/cities/export','Admin\CityController@export')->name('cities.export');
		
		Route::group(array('middleware' => ['acl.permitted']), function () {
			// MANUFACTURER MASTER //
			Route::resource('admin/manufacturer', 'Admin\SupplierMasterController',['except'=>['delete','show']]);
			Route::post('admin/manufacturer/delete','Admin\SupplierMasterController@delete')->name('manufacturer.delete');


			Route::get('admin/dashboard', 'Admin\DashboardController@index')->name('admin.dashboard');
			//Aakashi (product master)
			Route::resource('/admin/product','Admin\ProductMasterController',['except'=>['delete','show']]);
			Route::post('/admin/product/delete','Admin\ProductMasterController@delete')->name('addproduct.delete');
			Route::get('admin/data',['as'=>'admin.data.index','uses'=>'UserController@data']);
			//purchase requisition
			Route::resource('/admin/purchase-requisition','Admin\PurchaseRequisitionController',['except'=>['delete','show']]);
			
			//purchase requisition approval
			Route::resource('/admin/purchase-requisition-approval','Admin\PurchaseRequisitionApprovalController',['except'=>['delete','show']]);
			
			//end Aakashi
			
			//karishma (sales order)
			Route::resource('admin/salesorder', 'Admin\SalesOrderController',['except' => ['show']]);
			
			Route::post('/admin/salesorder/delete','Admin\SalesOrderController@delete')->name('salesorder.delete');
			
			//manage stock
			Route::get('admin/managestock', 'Admin\ManageStockController@index')->name('managestock.index');
			Route::get('admin/managestock/generatePoResponse','Admin\ManageStockController@generatePoResponse')->name('manage_stock.generateporesponse');
			
			//end karishma
			/**End Route for logout**/
			
			

			// COMPANY MASTER //
			Route::resource('admin/companymaster','Admin\CompanyMasterController',['except'=>['delete','show']]);
			

			// CUSTOMER MASTER //
			Route::resource('admin/customer','Admin\CustomerMasterController',['except'=>['delete','show']]);
			Route::post('admin/customer/delete','Admin\CustomerMasterController@delete')->name('customer.delete');
			

			Route::resource('admin/billing','Admin\BillingAddressController');
			Route::post('admin/billing/delete','Admin\BillingAddressController@delete')->name('billing.delete');
			//designation
			Route::resource('admin/designation', 'Admin\DesignationController',['except' => 'destroy']);
		
			Route::post('designation/delete', 'Admin\DesignationController@destroy')->name('designation.delete');
			//system user
			Route::post('admin/systemuser/{id}','Admin\SystemUserController@update')->name('systemuser.update');

			Route::resource('/permission','Admin\PermissionController');
			
			Route::resource('admin/systemuser','Admin\SystemUserController',['except' => 'update']);
			
			
			/*----------------City,state,role Route-------------*/
			Route::group(array('prefix' => 'admin'), function () {
				Route::resource('/cities', 'Admin\CityController',['except' => 'destroy']); 
			    Route::post('cities/delete', 'Admin\CityController@destroy')->name('cities.delete'); 
				Route::resource('/state', 'Admin\StateController',['except' => 'destroy']);
				Route::post('state/delete', 'Admin\StateController@destroy')->name('state.delete');
				Route::resource('/role', 'Admin\RoleController',['except' => 'destroy']);
				Route::post('role/delete', 'Admin\RoleController@destroy')->name('role.delete');

			});
		});
});
/*---------------------------------*/


Route::resource('admin/users', 'UserController',['except' => ['show']]);
Route::get('admin/dynamic-form',function(){
	return view('admin.dynamic-form.new');
});

Route::get('image/import',function(){
	return view('admin.image.index');
});
Route::get('admin/input',function(){
	return view('admin.input-type.index');
});
Route::get('/admin/input/form',function(){
	return view('admin.input-type.new');
});


