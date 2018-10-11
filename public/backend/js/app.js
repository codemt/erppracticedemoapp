(function () {
    'use strict';
    angular.module('ng-file-model', [])
	angular.module('funpApp',[])
	.directive('ngFiles',['$parse',function($parse){
	
	
			function fn_link(scope,element,attrs){
	
	
	
	
					var onChange = $parse(attrs.ngFiles);
	
	
					element.on('change',function(event){
	
	
							onChange(scope,{$files: event.target.files});
	
	
					});
	
	
			}
			return {
	
				link : fn_link
	
			}
	
	
	
	
	
	
	}])
	
	.controller('SalesOrderController as soc ',function($scope,$http){
	
	
				var formData = new formData();
	
				$scope.product_iamge = function($files){
	
						$scope.imagesrc = [];
	
	
						for(var i=0;i<$files.length;i++){
	
	
							var reader = new FileReader();
							reader.fileName = $files[i].name;
	
	
							reader.onload = function(event){
	
	
									var image = {};
									image.Name = event.target.fileName;
									image.Size = (event.total/1024).toFixed(2);
									image.Src = event.target.result;
									$scope.imagesrc.push(image);
									$scope.$apply();
	
	
							}
	
	
	
						}
	
						angular.forEach($files,function(value,key){
	
								formData.append(key,value);
	
	
						});
	
	
				}
	
	
	});
    
    angular.module('numbersOnly', [])
    .directive("numbersOnly", [function () {
        return {
	        require: 'ngModel',
	        link: function (scope, element, attr, ngModelCtrl) {
	            function fromUser(text) {
	                if (text) {
	                    var transformedInput = text.replace(/[^0-9]/g, '');

	                    if (transformedInput !== text) {
	                        ngModelCtrl.$setViewValue(transformedInput);
	                        ngModelCtrl.$render();
	                    }
	                    return transformedInput;
	                }
	                return undefined;
	            }            
	            ngModelCtrl.$parsers.push(fromUser);
	        }
	    };
    }]);
if( typeof exports !== 'undefined' ) {
    exports['default'] = angular.module('ng-file-model');
    module.exports = exports['default'];
}
})();

var salesorderApp = angular.module('salesorderApp', ['ng-file-model','ui.bootstrap'],function($interpolateProvider){
    $interpolateProvider.startSymbol('{%');
    $interpolateProvider.endSymbol('%}');
});

salesorderApp.controller('SalesOrderController', function($scope, $rootScope, $http, $uibModal, $timeout) {
	
	var soc = this;
	soc.products = productData;

	soc.suppliers = supplierData;

	$scope.salesorder = {};
	$scope.salesorder = sales_order;


	if (sales_order.check_billing == 1) {
		$scope.salesorder.check_billing = true;
	}else{
		$scope.salesorder.check_billing = false;
	}

	$scope.salesorder.billing_title = sales_order.billing_title;
	
	$scope.orderTotal = 0;
	$scope.orderTotalQty = 0;
	$scope.salesorder.discount_applied = 0;
	$scope.salesorder.total_value = 0;
	soc.cartProducts = sales_order_item;
	$scope.selectedmanuclearance = 'Yes';
	$scope.salesorder.so_no = so_no;
	
	$scope.salesorder.total_amount = sales_order.total_amount;
	$scope.salesorder.total_tax_amount = sales_order.total_tax_amount;
	$scope.orderTotal = sales_order.total_amount;
	$scope.orderTotalQty = sales_order.total_qty;
	$scope.orderTaxTotal = sales_order.total_tax_amount;
	//$scope.getTheFiles = '';
	$scope.salesorder.product_image = '';
	$scope.add_select = 0;
	$scope.sales_order_data = sales_order_data;
	$scope.order_item_pdf = order_item_pdf;
	$scope.hsn_codes = hsn_codes;
	try{
		if (orderProducts && orderProducts.length > 0) {
			// console.log('hi');
				_.each(orderProducts, function(orderProduct, key){

					if (parseInt(orderProduct.remain_qty,10) == -1) {
						orderProduct.remain_qty = Infinity;
					}

					var cartProduct = {
						quantity: $scope.selectedQuantity,
						max_discount: $scope.max_discount,
						productname: product.name_description,
						suppliers: suppliers.supplier_name,
						supplier_id: suppliers.id,
						price: product.price,
						unit_value: $scope.selectedunitvalue,
						manu_clearance: $scope.selectedmanuclearance,
						min_qty: parseInt(product.qty,10),
						id: product.id,
						model_no: product.model_no,
						tax:product.tax,
						editing: false,
						tax_subtotal:$scope.tax_subtotal,
						discount_applied:(100 - (product.unit_value*100/product.price)).toFixed(2),
						total_value: ($scope.selectedQuantity * $scope.selectedunitvalue),
						tax_value:(($scope.selectedQuantity * $scope.selectedunitvalue)*product.tax)/100,
					};

					soc.cartProducts[orderProduct.id] = cartProduct;
				});

				orderTotal();
		}
		
	}
	catch(err){

	}
	//set new qty and discount as by default 0 so total is default showing 0
	_.each(soc.products, function(value, key){
		soc.products[key].new_qty = 0;
		soc.products[key].quantity = 0;
		soc.products[key].discount = 0;
		soc.products[key].unit_value = 0;
		soc.products[key].tax = 0;
		soc.products[key].manu_clearance = 'Yes';
		
		if (parseInt(value.qty,10) < 0) {
			soc.products[key].qty = Infinity;
		}
	});
	soc.addItem = function() {

		// console.log($scope.selectedProduct,$scope.selectedQuantity,$scope.selectedunitvalue);

		if ($scope.selectedProduct == undefined || $scope.selectedQuantity == undefined || $scope.selectedunitvalue == undefined) {
			$scope.add_select = 1;
			return;
		}

		if ($scope.selectedQuantity <= 0 || !_.isNumber($scope.selectedQuantity)) {
			return;
		}

		var foundedCartProduct = soc.cartProducts[parseInt($scope.selectedProduct,10)];

		if (foundedCartProduct) {

			var currentQuantity = foundedCartProduct.quantity;
			var changedQuantity = currentQuantity + $scope.selectedQuantity;

			var currentUnitValue = foundedCartProduct.unit_value;
			var changedUnitValue =parseInt(currentUnitValue) + parseInt($scope.selectedunitvalue);
			
			// if (changedQuantity > foundedCartProduct.min_qty) {
			// 	showAlert('Quantity limit exceed','You have only '+ foundedCartProduct.min_qty + ' no. of quantity is remaining for this item');
			// 	return false;
			// }

			soc.cartProducts[$scope.selectedProduct].quantity = changedQuantity;
			soc.cartProducts[$scope.selectedProduct].unit_value = changedUnitValue;

			orderTotal();

			// $scope.$apply($scope.selectedProduct = '');
			// $scope.selectedProduct = '';

			// $('#products').parent().find('.select2').siblings('select').select2('val','');

		} else {

			var product = _.first(_.where(soc.products,{ id: parseInt($scope.selectedProduct,10) }));

			var suppliers = _.first(_.where(supplierData,{ id: parseInt($scope.selectedSupplier,10) }));
			
			if(product && suppliers) {
				
				var cartProduct = {
					quantity: $scope.selectedQuantity,
					max_discount: parseInt(product.max_discount),
					suppliers: suppliers.supplier_name,
					supplier_id: suppliers.id,
					productname: product.name_description,
					price: product.price,
					unit_value: $scope.selectedunitvalue,
					manu_clearance: $scope.selectedmanuclearance,
					min_qty: parseInt(product.qty,10),
					id: product.id,
					model_no: product.model_no,
					tax:product.tax,
					editing: false,
					discount_applied:(100 - ($scope.selectedunitvalue*100/product.price)).toFixed(2),
					total_value: ($scope.selectedQuantity * $scope.selectedunitvalue),
					tax_value:(($scope.selectedQuantity * $scope.selectedunitvalue)*product.tax)/100,
				};

				soc.cartProducts[product.id] = cartProduct;
				// $scope.$apply($scope.selectedProduct = '');
				// $scope.selectedProduct = '';
				// $('#products').parent().find('.select2').siblings('select').select2('val','');
			}

			orderTotal();
		}

		$scope.selectedQuantity = 0;
		$scope.selectedunitvalue = 0;
		$scope.supplier_error = 0;
		$scope.quantity_error = 0;
		$scope.unit_value_error = 0;

		$scope.add_select = 0;

		$('#products').select2('val','');
		$('#supplier').select2('val','');
	}
	soc.doneChangeQty = function(cartProduct) {
		cartProduct.editing = false;

		if (cartProduct.quantity <= 0) {
			soc.removeItem(cartProduct);
		}
		
		// if (cartProduct.quantity > cartProduct.min_qty) {
		// 	showAlert('Quantity limit exceed','You have only'+ cartProduct.min_qty + ' no. of quantity is remaining for this item');
		// 	cartProduct.quantity = 0;
		// 	return false;
		// }

		orderTotal();
	}
	soc.editQty = function(cartProduct){
		cartProduct.editing = true;
	}
	soc.doneChangeUnitValue = function(cartProduct) {
		// console.log(cartProduct);
		cartProduct.unitediting = false;

		if (cartProduct.quantity <= 0) {
			soc.removeItem(cartProduct);
		}
		// if (cartProduct.quantity > cartProduct.min_qty) {
		// 	showAlert('Quantity limit exceed','You have only'+ cartProduct.min_qty + ' no. of quantity is remaining for this item');
		// 	return false;
		// }
		cartProduct.discount_applied = (100 - (cartProduct.unit_value*100/cartProduct.price)).toFixed(2)

		cartProduct.tax_value = (((cartProduct.quantity * cartProduct.unit_value)*cartProduct.tax)/100).toFixed(2),
		
		// if(cartProduct.unit_value == cartProduct.price || cartProduct.unit_value > cartProduct.price){
		// 	cartProduct.discount_applied = 0;
		// }else{
		// 	cartProduct.discount_applied = cartProduct.discount_applied;
		// }
		// if(cartProduct.unit_value > cartProduct.price){
		// 	showAlert('Quantity limit exceed','You have only'+ cartProduct.min_qty + ' no. of quantity is remaining for this item');
		// 	return false;
		// }

		orderTotal();

	}
	soc.editUnitValue = function(cartProduct){
		cartProduct.unitediting = true;
	}
	soc.doneChangeManuClearance = function(cartProduct) {
		cartProduct.manuclearanceediting = false;

		if (cartProduct.quantity <= 0) {
			soc.removeItem(cartProduct);
		}
		
		// if (cartProduct.quantity > cartProduct.min_qty) {
		// 	showAlert('Quantity limit exceed','You have only'+ cartProduct.min_qty + ' no. of quantity is remaining for this item');
		// 	cartProduct.quantity = 0;
		// 	return false;
		// }

		orderTotal();
	}
	soc.editManuClearance = function(cartProduct){
		cartProduct.manuclearanceediting = true;
	}
	soc.removeItem = function(product_item){
		
		if (soc.cartProducts[product_item.id]) {
			delete soc.cartProducts[product_item.id];
			orderTotal();
		}
		if(removeproducturl != ''){
			$http({
	            method : 'post',
	            url : removeproducturl,
	            headers: {
	                'Content-Type': 'application/json'
	            },
	            data : {'product_item':product_item}
	        }).success(function(response) {
	        	
	        });
		}
	}
	soc.getProduct = function(){
		var company_id = sales_order.company_id;
		var supplier_id = $scope.selectedSupplier;
		$('#loader').show();
		if (supplier_id == null) {
			supplier_id = $scope.selectedSupplierForMulti
		}
		// console.log(supplier_id);
		soc.products = null;
		$http({
            method : 'post',
            url : getproducturl,
            headers: {
                'Content-Type': 'application/json'
            },
            data : {'company_id':company_id,'supplier_id':supplier_id}
        }).success(function(response) {
        	$('#loader').hide();
        	if(response.length == 0){
        		toastr.error('There Were no products of these supplier');
        	}else{
        		soc.products = response;
        	}
        });
	}

	soc.openModal = function() {
        $('#loader').show();

		supplier_id = $scope.selectedSupplierForMulti

		$http({
            method : 'post',
            url : getsupplierproducturl,
            headers: {
                'Content-Type': 'application/json'
            },
            data : {'supplier_id':supplier_id}
        }).success(function(response) {
        	$('#loader').hide();

        	if(response.length == 0){
        		
        	}else{
        		$scope.new_product = response;
        		_.each($scope.new_product, function(value, key){
					$scope.new_product[key].new_qty = 0;
					$scope.new_product[key].quantity = 0;
					$scope.new_product[key].discount = 0;
					$scope.new_product[key].unit_value = 0;
					$scope.new_product[key].manu_clearance = 'Yes';
				});
        		// $scope.supplier_name = response.supplier_name;
				//open modal which tempateurl is salesorderHtml

				$rootScope.modalInstance = $uibModal.open({
					controller: 'SalesOrderController',
					templateUrl: 'salesorderHtml.html',
					controllerAs: 'soc',
					size: 'lg',
					scope : $scope,
					resolve: {
						items: function () {
							return $scope.selectedSupplierForMulti;
						}
					}
				});

				//show item in table after multiple add
				$rootScope.modalInstance.result.then(function (new_product) {
					if ($scope.new_product) {
				      _.each($scope.new_product, function(product, key){
							if (product.new_qty > 0) {
								var cartProduct = {
									quantity: product.new_qty,
									max_discount:product.max_discount,
									suppliers: product.supplier_name,
									supplier_id: product.supplier_id,
									productname: product.name_description,
									price: product.price,
									unit_value: product.unit_value,
									manu_clearance: product.manu_clearance,
									min_qty: parseInt(product.qty,10),
									id: product.id,
									model_no: product.model_no,
									tax:product.tax,
									editing: false,
									discount_applied:(100 - (product.unit_value*100/product.price)).toFixed(2),
									total_value: (product.new_qty * product.unit_value),
									tax_value:((product.new_qty * product.unit_value)*product.tax)/100,
								};
								soc.cartProducts[product.id] = cartProduct;
							}
						});
				      	orderTotal();
					}
			    });
        	}
        });

	}
	soc.modalOk = function () {
		// console.log(soc.products);
		_.each(soc.products, function(product, key){
			// if(product.new_qty <= product.qty){
				$rootScope.modalInstance.close(soc.products);
			// }else{
				// showAlert('Quantity limit exceed','You have only'+ product.qty + ' no. of quantity is remaining for this item');
				 // var modalInstance = $uibModal.open(soc.products);
				// return false;
			// }
		});
		// console.log(soc.products);
    };

    soc.modalCancel = function () {
      	$rootScope.modalInstance.close(false);
    };
    soc.openSoView = function() {
		//open modal which tempateurl is salesorderHtml
		$rootScope.modalInstance = $uibModal.open({
			controller: 'SalesOrderController',
			templateUrl: 'salesorderview.html',
			controllerAs: 'soc',
			scope: $scope,
			size: 'lg',
			resolve: {
				items: function () {

					return $scope.sales_order_data;
				}
			}
		});
	}
    soc.completeSalesOrder = function(btn_val){
    	$scope.error_message = '';
    	$scope.salesorder.product = soc.cartProducts; 
        $scope.salesorder.save_button = btn_val;
        var data = $scope.salesorder;
        console.log(data)
        $('#loader').show();
    	$("[id$='_error']").empty();
        $("[id$='_error_div']").removeClass('has-error');
        if(btn_val == "approve"){
        	url = approve_url;
        }else if(btn_val == "on_hold"){
        	url = on_hold_url;
        }
    	$http({
            method : method,
            url : url,
            headers: {
                'Content-Type': 'application/json'
            },
            data : data
        }).success(function(response) {

			console.log(response);
        	$('#loader').hide();
            if (response.redirect != 'back') {
                toastr.success(msg);
            	setTimeout(function() {
            		location.href = response.redirect;
                },1000);
            	
            }else{
                toastr.success(msg);
            	setTimeout(function() {
            		location.reload();
                },1000);
            }
        }).error(function(error_response){
			console.log(error_response);
        	$('#loader').hide();
        	toastr.error('There Were some errors');
            $.each(error_response.errors,function(k,v){
            	$('#'+k+'_error').text(v);
                $('#'+k+'_error_div').addClass('has-error');
            });
        });
    };
    soc.SalesOrderView = function(){
    	var form_data = $scope.salesorder;
    	
    	form_data['items'] = soc.cartProducts;
    	$("[id$='_error']").empty();
        $("[id$='_error_div']").removeClass('has-error');

    	$http({
            method : method,
            url : view_url,
            headers: {
                'Content-Type': 'application/json'
            },
            data : form_data
        }).success(function(response) {
        	$scope.view_data = response;
        	//open modal which tempateurl is salesorderHtml
			$rootScope.modalInstance = $uibModal.open({
				controller: 'SalesOrderController',
				templateUrl: 'salesorderview.html',
				controllerAs: 'soc',
				scope: $scope,
				size: 'lg',
				resolve: {
					items: function () {

						return $scope.view_data;
					}
				}
			});
        	$('#loader').hide();
            // if (response.redirect != 'back') {
            //     toastr.success(msg);
            // 	setTimeout(function() {
            // 		location.href = response.redirect;
            //     },50);
            	
            // }else{
            //     toastr.success(msg);
            // 	setTimeout(function() {
            // 		location.reload();
            //     },50);
            // }
        }).error(function(error_response){
        	$('#loader').hide();
        	toastr.error('There Were some errors');
            $.each(error_response.errors,function(k,v){
            	$('#'+k+'_error').text(v);
                $('#'+k+'_error_div').addClass('has-error');
            });
        });
    };
    $scope.updateValue = function(){
		fright = $scope.salesorder.fright;
		orderTotals = $scope.orderTotal;
		orderTaxTotal = $scope.orderTaxTotal;
		pkg_fwd = $scope.salesorder.pkg_fwd;

			// get taxrate
			var e = document.getElementById("taxrate");
			var taxrate = e.options[e.selectedIndex].value;
	
			// convert it into number.
			var finaltax = parseInt(taxrate);

    	if($scope.orderTotal == undefined || $scope.orderTotal == ''){
    		orderTotals = 0;
    	}if($scope.orderTaxTotal == undefined || $scope.orderTaxTotal == ''){
    		orderTaxTotal = 0;
    	}if($scope.salesorder.pkg_fwd == undefined || $scope.salesorder.pkg_fwd == ''){
    		pkg_fwd = 0;
    	}if($scope.salesorder.fright == undefined || $scope.salesorder.fright == ''){
    		fright = 0;
		}
		if(taxrate == undefined || taxrate == ''){
    		finaltax = 18;
		}
		tax_subtotals = ((parseFloat(fright) + parseFloat(pkg_fwd))*finaltax)/100;
		$scope.salesorder.tax_subtotal = tax_subtotals;
		
    	$scope.salesorder.grand_total = (parseFloat(orderTotals) + parseFloat(orderTaxTotal) + parseFloat(pkg_fwd) + parseFloat(fright) + parseFloat($scope.salesorder.tax_subtotal)).toFixed(2);
    }

    $scope.change_status = function(status){
    	return status;
    }

    $scope.companyChange = function(){
    	$scope.soc.cartProducts = {};
    	$scope.orderTotal = '';
		$scope.orderTaxTotal = '';
		$scope.salesorder.total_amount = '';
		$scope.salesorder.total_tax_amount = '';
		
		var company_id = sales_order.company_id;

		soc.customers = null;
		soc.suppliers = null;
		soc.products = null;
		
		$http({
            method : 'post',
            url : getcustomerurl,
            headers: {
                'Content-Type': 'application/json'
            },
            data : {'company_id':company_id}
        }).success(function(response) {
        	if(response.length == 0){
        		toastr.error('There Were no Customer of these company');
        	}else{
        		soc.customers = response.customers;
        		soc.suppliers = response.suppliers;
        	}
        });

    }
	function orderTotal(){
		var total = 0;
		var tax_total = 0;
		var total_qty = 0;
		_.each(soc.cartProducts, function(value, key){
			if (soc.cartProducts[key] != undefined) {
				total = total + (value.quantity * value.unit_value);
				tax_total = parseFloat(tax_total) + parseFloat(value.tax_value);
				total_qty = total_qty + value.quantity;
			}
		});
		$scope.orderTotal = total;
		$scope.orderTaxTotal = parseFloat(tax_total);
		$scope.orderTotalQty = total_qty;
		$scope.salesorder.total_amount = $scope.orderTotal;
		$scope.salesorder.total_qty = $scope.orderTotalQty;
		$scope.salesorder.total_tax_amount = $scope.orderTaxTotal;

		$scope.updateValue();
	}
	function showAlert(title,text){
		toastr.error(
			'<b>'+title+'<b><br><p>'+text+'</p>'
		);
		return false;
	}
});