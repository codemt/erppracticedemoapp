<?php
namespace App\Providers;

use Illuminate\Support\Facades\Event;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        'App\Events\Event' => [
            'App\Listeners\EventListener',
        ],
        'App\Events\AddProductMasterEvent' => [
            'App\Listeners\AddProductListener',
            'App\Listeners\ProductManageStockListener'
        ],
        'App\Events\UpdateProductMasterEvent' => [
            'App\Listeners\UpdateProductMasterListener',
            'App\Listeners\UpdateProductManageStockListener'
        ],
        'App\Events\PurchaseRequisitionEvent' => [
            'App\Listeners\PurchaseRequisitionListener'
        ],
        'App\Events\PurchaseRequisitionEvent' => [
            'App\Listeners\PurchaseRequisitionListener'
        ],
        'App\Events\UpdatePurchaseRequisitionEvent' => [
            'App\Listeners\UpdatePurchaseRequisitionListener'
        ],
        'App\Events\UpdatePurchaseRequisitionApprovalEvent' => [
           'App\Listeners\UpdatePurchaseRequisitionApprovalListener',
            'App\Listeners\PurchaseRequisitionCheckThresholdListener'
        ],
        'App\Events\SalesOrderCreateEvent' => [
            'App\Listeners\SalesOrderCreateListener',
            'App\Listeners\SalesOrderCreateManageStockListener'
        ],
        'App\Events\SalesOrderUpdateEvent' => [
            'App\Listeners\SalesOrderUpdateListener',
            'App\Listeners\SalesOrderUpdateManageStockListener'
        ],
        'App\Events\SalesOrderApprovalUpdateEvent' => [
            'App\Listeners\SalesOrderApprovalUpdateListener'
        ],

        'App\Events\SystemUser\DesignationEvent' => [
            'App\Listeners\SystemUser\DesignationListener',
        ],

        'App\Events\SupplierMaster\InsertSupplierMasterEvent' => [
            'App\Listeners\SupplierMaster\InsertSupplierMasterListener',
        ],
        'App\Events\SupplierMaster\UpdateSupplierMasterEvent' => [
            'App\Listeners\SupplierMaster\UpdateSupplierMasterListener',
        ],

        'App\Events\CompanyMaster\UpdateCompanyMasterEvent' => [
            'App\Listeners\CompanyMaster\UpdateCompanyMasterListener',
        ],

        'App\Events\CustomerMaster\InsertCustomerMasterEvent' => [
            'App\Listeners\CustomerMaster\InsertCustomerMasterListener',
        ],
        'App\Events\CustomerMaster\UpdateCustomerMasterEvent' => [
            'App\Listeners\CustomerMaster\UpdateCustomerMasterListener',
        ],

        'App\Events\SystemUser\InsertSystemUserEvent' => [
            'App\Listeners\SystemUser\InsertSystemUserListener',
        ],
        'App\Events\SystemUser\UpdateSystemUserEvent' => [
            'App\Listeners\SystemUser\UpdateSystemUserListener',
        ],

        'App\Events\BillingAddress\InsertBillingAddressEvent' => [
            'App\Listeners\BillingAddress\InsertBillingAddressListener',
        ],
        'App\Events\BillingAddress\UpdateBillingAddressEvent' => [
            'App\Listeners\BillingAddress\UpdateBillingAddressListener',
        ],
        'App\Events\AddDistributorEvent' => [
            'App\Listeners\AddDistributorListener',
        ],
        'App\Events\UpdateDistributorEvent' => [
            'App\Listeners\UpdateDistributorListener',
        ],
        'App\Events\StateEvent' => [
            'App\Listeners\SateListener',
        ],
        'App\Events\CityEvent' => [
            'App\Listeners\CityListener',
        ],
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        parent::boot();

        //
    }
}
