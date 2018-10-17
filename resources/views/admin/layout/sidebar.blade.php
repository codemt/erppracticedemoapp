<aside class="main-sidebar hidden-print">
    <section class="sidebar">
        <!-- <div class="search-panel">
            <div class="search-box">
              <div class="icon pull-left"><i class="fa fa-search"></i></div>
              <input class="text" type="text" placeholder="Special Search if require">
              <div class="clearfix"></div>
            </div>
        </div> -->
        <br>
        <!-- Sidebar Menu-->
         <div class="sidebar-content">
            <ul class="sidebar-menu" id="nav-accordion">
                <ul class="sidebar-menu" id="nav-accordion">
                 <li class="sub-menu @if(Request::segment(2) == 'product' || Request::segment(2) == 'manufacturer' || Request::segment(2) == 'customer' || Request::segment(2) == 'companymaster' || Request::segment(2) == 'distributor' || Request::segment(2) == 'state' || Request::segment(2) == 'cities') active @endif">
                    <a href="javascript:;">
                        <i class="fas fa-database"></i>
                        <span>Masters</span>
                        <i class="fas fa-angle-right"></i>
                    </a>
                    <ul class="treeview-menu">
                        @if(App\Helpers\DesignationPermissionCheck::isPermitted('product.index'))
                            <li class=" @if(Request::segment(2) == 'product') active @endif">
                                <a href="<?=route('product.index')?>">
                                <i class="fas fa-boxes"></i>
                                <span>Products</span>
                                </a>
                            </li>
                        @endif    
                        @if(App\Helpers\DesignationPermissionCheck::isPermitted('manufacturer.index'))
                            <li class=" @if(Request::segment(2) == 'manufacturer') active @endif">
                                <a href="<?= route('manufacturer.index') ?>">
                                <i class="fas fa-conveyor-belt"></i>
                                <span>Manufacturers</span>
                                </a>
                            </li>
                        @endif
                        @if(App\Helpers\DesignationPermissionCheck::isPermitted('distributor.index'))
                            <li class=" @if(Request::segment(2) == 'distributor') active @endif">
                                <a href="<?= route('distributor.index') ?>">
                                <i class="fas fa-conveyor-belt"></i>
                                <span>Distributors</span>
                                </a>
                            </li>
                        @endif    
                        @if(App\Helpers\DesignationPermissionCheck::isPermitted('companymaster.index'))
                            <li class=" @if(Request::segment(2) == 'companymaster') active @endif">
                                <a href="<?= route('companymaster.index') ?>">
                                <i class="fas fa-building"></i>
                                <span>Company</span>
                                </a>
                            </li>
                        @endif  
                        @if(App\Helpers\DesignationPermissionCheck::isPermitted('customer.index'))  
                            <li class=" @if(Request::segment(2) == 'customer') active @endif">
                                <a href="<?= route('customer.index') ?>">
                                <i class="fas fa-address-card"></i>
                                <span>Customers</span>
                                </a>
                            </li>
                        @endif 
                        @if(App\Helpers\DesignationPermissionCheck::isPermitted('state.index'))     
                            <li class=" @if(Request::segment(2) == 'state') active @endif">
                                <a href="<?= route('state.index') ?>">
                                <i class="fas fa-flag"></i>
                                <span>State</span>
                                </a>
                            </li>
                        @endif    
                        @if(App\Helpers\DesignationPermissionCheck::isPermitted('cities.index'))     
                            <li class=" @if(Request::segment(2) == 'cities') active @endif">
                                <a href="<?= route('cities.index') ?>">
                                <i class="fas fa-home"></i>
                                <span>City</span>
                                </a>
                            </li>
                        @endif                        
                    </ul>   
                </li>
                @if(App\Helpers\DesignationPermissionCheck::isPermitted('purchase-requisition.index') || App\Helpers\DesignationPermissionCheck::isPermitted('purchase-requisition-approval.index') )
                <li class="sub-menu @if(Request::segment(2) == 'purchase-requisition' || Request::segment(2) == 'purchase-requisition-approval') active @endif">
                    <a href="javascript:;">
                        <i class="fas fa-warehouse-alt"></i> 
                        <span> Purchase Requisition</span>
                        <i class="fas fa-angle-right"></i>
                    </a>
                    <ul class="treeview-menu">
                        @if(App\Helpers\DesignationPermissionCheck::isPermitted('purchase-requisition.index'))
                        <li class=" @if(Request::segment(2) == 'purchase-requisition') active @endif">
                            <a href="<?=route('purchase-requisition.index')?>">
                            <i class="fas fa-warehouse-alt"></i>
                            <span>New</span>
                            </a>
                        </li>
                        @endif
                        @if(App\Helpers\DesignationPermissionCheck::isPermitted('purchase-requisition-approval.index'))
                        <li class=" @if(Request::segment(2) == 'purchase-requisition-approval') active @endif">
                            <a href="<?=route('purchase-requisition-approval.index')?>">
                            <i class="fas fa-box-check"></i>
                            <span>Approval</span>
                            </a>
                        </li>
                        @endif
                    </ul>   
                </li>
                @endif
                @if(App\Helpers\DesignationPermissionCheck::isPermitted('salesorder.index'))
                <li class=" @if(Request::segment(2) == 'salesorder') active @endif">
                    <a href="<?=route('salesorder.index')?>">
                    <i class="fas fa-cart-arrow-down"></i>
                    <span>Sales Order</span>
                    </a>
                </li>
                @endif
                @if(App\Helpers\DesignationPermissionCheck::isPermitted('managestock.index'))
                    <li class=" @if(Request::segment(2) == 'managestock') active @endif">
                        <a href="<?= route('managestock.index') ?>">
                        <i class="fas fa-inventory"></i>
                        <span>Manage Stock</span>
                        </a>
                    </li>
                @endif
                @if(App\Helpers\DesignationPermissionCheck::isPermitted('designation.index') || App\Helpers\DesignationPermissionCheck::isPermitted('systemuser.index'))
                    <li class="sub-menu @if(Request::segment(2) == 'designation' || Request::segment(2) == 'systemuser') active @endif">
                        <a href="javascript:;">
                            <i class="fas fa-bars"></i>
                            <span>System User</span>
                            <i class="fas fa-angle-right"></i>
                        </a>
                        <ul class="treeview-menu">
                            @if(App\Helpers\DesignationPermissionCheck::isPermitted('designation.index'))
                                <li class="@if(Request::segment(2) == 'designation') active @endif">
                                    <a href="<?= route('designation.index') ?>">
                                    <i class="fas fa-users"></i>
                                    <span>Designation</span>
                                    </a>
                                </li>
                            @endif   
                            @if(App\Helpers\DesignationPermissionCheck::isPermitted('systemuser.index'))
                                <li class="@if(Request::segment(2) == 'systemuser') active @endif">
                                    <a href="<?= route('systemuser.index') ?>">
                                    <i class="fas fa-user"></i>
                                    <span>Users</span>
                                    </a>
                                </li>
                            @endif    
                        </ul>   
                    </li>
                 @endif
                 @if(App\Helpers\DesignationPermissionCheck::isPermitted('emails.index'))   
                 <li class=" @if(Request::segment(2) == 'systemuser') active @endif">
                    <a href="{{ route('emails.index') }}">
                    <i class="fa fa-envelope"></i>
                    <span>Send Emails </span>
                    </a>
                </li>
                @endif
                <!-- <li class=" @if(Request::segment(2) == 'billing') active @endif">
                    <a href="<?= route('billing.index') ?>">
                    <i class="fa fa-user"></i>
                    <span>Billing Address</span>
                    </a>
                </li> -->
               <!--  <li class="sub-menu @if(Request::segment(1) == 'admin') active @endif">
                    <a href="javascript:;">
                        <i class="fa fa-bars"></i>
                        <span>System User</span>
                        <i class="fas fa-angle-right"></i>
                    </a>
                    <ul class="treeview-menu">
                        <li class="@if(Request::segment(2) == 'role') active @endif">
                            <a href="">
                            <i class="fa fa-pencil-square-o"></i>
                            <span>Role</span>
                            </a>
                        </li>
                        <li class="@if(Request::segment(2) == 'systemuser') active @endif">
                            <a href="<?= route('systemuser.index') ?>">
                            <i class="fa fa-users"></i>
                            <span>Users</span>
                            </a>
                        </li>
                    </ul>   
                </li> -->
                </ul>
                <li class="spacer"></li>
                <li class="logout">
                    <a href="javascript:;">
                        <i class="fas fa-user"></i>
                        <span>{{Auth::guard('admin')->user()->name}}</span>
                        <i class="fas fa-angle-right"></i>
                    </a>
                    <ul class="logout-modal">
                        <!-- <li>
                            <a href="/admin/change-password">Change Password</a>
                        </li> -->
                        <li>
                            <a href="<?=route('admin.logout')?>">Logout</a>
                        </li>
                    </ul>
                </li>
            </ul>
        </div>
    </section>
</aside>