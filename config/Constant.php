<?php
define('IS_SECURE',false);
define('LOGIN_BG', '../backend/images/banner1.jpg');
define('LOCAL_IMAGE_PATH','http://triton.local/upload/');//for fetch
//define('UPLOAD_PATH', realpath(__DIR__ . "/"));
define('IMAGE_PATH', "http://erpproductsystem.local/");
define('XML_PATH', "http://erpproductsystem.local/");
return [
	'payment_terms'=>[''=>'Select Payment Terms','advance and balance against PI'=>'advance and balance against PI','100% against PI'=>'100% against PI','100% against the dispatch'=>' 100% against the dispatch','30 Days Credit'=>'30 Days Credit','30 Days PDC'=>'30 Days PDC','45 Days Credit'=>'45 Days Credit', '45 days PDC'=>'45 days PDC','60 Days Credit'=>'60 Days Credit', '60 days PDC'=>'60 days PDC', '90 Days Credit'=>'90 Days Credit','90 days PDC'=>'90 days PDC','100% against LC'=>'100% against LC'],
	'manu_clearance'=>['Yes'=>'Yes','No'=>'No'],
	'part_shipment'=>['Yes'=>'Yes','No'=>'No'],
	'trasport'=>['By Air'=>'By Air','By Road'=>'By Road'],
	'taxrate' => ['5%'=>'5%','12%'=>'12%','18%'=>'18%','28%'=>'28%'],
	'threshold_value' => 500000,
	'dispatch_value' => [
		'' => 'Select Dispatch Through',
		'by air' => 'By Air',
		'by road' => 'By Road',
		'self collect' => 'Self Collect',
		'hand delivery' => 'Hand Delivery'
	],
	'status'=>
		[
			'waiting for approval'=>'waiting for approval',
			'waiting for accountant'=>'waiting for accountant',
			'waiting for admin'=>'waiting for admin',
			'waiting for owner'=>'waiting for owner',
			'pending'=>'pending',
			'deleted'=>'deleted',
			'approve'=>'approve',
			'ammended approve'=>'ammended approve',
			'received'=>'received',
			'onhold' => 'onhold'
		],
	'superadmin'=>1,
	'account'=>5,
	'admin'=>7,
	'warehouse'=>4,
	'regional_manager'=>7,
	'sales'=>3,
	'status_amen_approve' => 
		[
			'approve'=>'approve',
			'ammended approve'=>'ammended approve'
		],
	'status_pending_all' => 
		[
			'waiting for approval'=>'waiting for approval',
			'waiting for admin'=>'waiting for admin',
			'waiting for owner'=>'waiting for owner',
			'pending'=>'pending',
		],
	'status_waiting_all' =>
		[
			'waiting for approval'=>'waiting for approval',
			'waiting for admin'=>'waiting for admin',
			'waiting for owner'=>'waiting for owner'
		],
	'status_waiting_onhold' =>
		[
			'waiting for approval'=>'waiting for approval',
			'waiting for admin'=>'waiting for admin',
			'waiting for owner'=>'waiting for owner',
			'onhold' => 'onhold'
		],
	'status_pending_onhold' =>
		[
			'pending'=>'pending',
			'onhold' => 'onhold'
		],
	'status_waiting_pending_hold' =>
		[
			'waiting for approval'=>'waiting for approval',
			'waiting for admin'=>'waiting for admin',
			'pending'=>'pending',
			'onhold' => 'onhold'
		],
	'status_waiting_pending' =>
		[
			'waiting for approval'=>'waiting for approval',
			'waiting for admin'=>'waiting for admin',
			'pending'=>'pending'
		],
	'status_pending_hold_owner' =>
		[
			'waiting for owner'=>'waiting for owner',
			'pending'=>'pending',
			'onhold' => 'onhold'
		],
	'status_pending_hold_approval' =>
		[
			'waiting for approval'=>'waiting for approval',
			'pending'=>'pending',
			'onhold' => 'onhold'
		],
	'status_approval_hold' =>
		[
			'waiting for approval'=>'waiting for approval',
			'onhold' => 'onhold'
		],
	'status_approval_hold_pending' =>
		[
			'waiting for approval'=>'waiting for approval',
			'onhold' => 'onhold',
			'pending'=>'pending'
		],
	'status_admin_hold' =>
		[
			'waiting for admin'=>'waiting for admin',
			'onhold' => 'onhold'
		],
	'status_owner_hold' =>
		[
			'waiting for owner'=>'waiting for owner',
			'onhold' => 'onhold'
		],
		'login_link'=>'admin.login',
	// 'status_approval_hold_pending_ammen_app' =>
	// 	[
	// 		'waiting for approval'=>'waiting for approval',
	// 		'onhold' => 'onhold',
	// 		'pending'=>'pending',
	// 		'ammended approve'=>'ammended approve',
	// 		'approve'=>'approve'
	// 	],
 
	'Stellar' => '1',
	'Triton' => '2'
];